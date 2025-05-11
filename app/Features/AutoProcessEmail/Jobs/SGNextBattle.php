<?php

namespace App\Features\AutoProcessEmail\Jobs;

use App\Features\AutoProcessEmail\Jobs\AutoProcessEmailJob;
use App\Services\LanguageDectorService;
use App\Features\AutoProcessEmail\AutoProcessResponseTypeEnum;
use App\Features\AutoProcessEmail\Data\AutoProcessedEmailData;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
class SGNextBattle extends AutoProcessEmailJob
{
    private const BASE_URI = 'https://oddesseysms.nl';
    /**
     * Get allowed languages.
     *
     * @return array
     */
    public function getAllowedLanguages(): array
    {
        return [
            LanguageDectorService::ENGLISH_LANGUAGE
        ];
    }

    /**
     * POST https://oddesseysms.nl/ch9/lookup?msisdn=00xxxx
     * Sample response:{"active":true,"msisdn":"00420722109300","activated":"1745201345","stopped":"","operator":"23002","opt-out":"unsubscribe"},
     * Sample response:{"active":false,"msisdn":"00420736166731","activated":"1745202969","stopped":"1746429802","operator":"23003","opt-out":"unsubscribe"}}
     */
    public function getSubscriptions(string $mobileNumber): array
    {
        $response = Http::timeout($this->timeout)
            ->retry($this->tries, 100)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('https://portal.telcosupport.com/phpinfo.php', [
                'token' => 'nakuit',
                'endpoint' => self::BASE_URI . "/sg1/lookup?msisdn=" . $this->normalize($mobileNumber),
                'method' => 'POST',
            ])
            ->throw();

        return $response->json();

        $response = Http::timeout($this->timeout)
            ->retry($this->tries, 100)
            ->withOptions([
                'query' => [
                    'msisdn' => $this->normalize($mobileNumber),
                ],
            ])
            ->post(self::BASE_URI . "/ch9/lookup")
            ->throw();

        return $response->json();
    }

    /**
     * POST https://oddesseysms.nl/sg1/unsubscribe
     * Sample response:{"success":true,"msisdn":"00420704338661","activated":"1745207620","stopped":""}}
     */
    public function unsubscribe(string $mobileNumber): array
    {
        $response = Http::timeout($this->timeout)
            ->retry($this->tries, 100)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('https://portal.telcosupport.com/phpinfo.php', [
                'token' => 'nakuit',
                'endpoint' => self::BASE_URI . "/sg1/unsubscribe?msisdn=" . $this->normalize($mobileNumber),
                'method' => 'POST',
            ])
            ->throw();

        return $response->json();

        $response = Http::timeout($this->timeout)
            ->retry($this->tries, 100)
            ->withOptions([
                'query' => [
                    'msisdn' => $this->normalize($mobileNumber),
                ],
            ])
            ->post(self::BASE_URI . "/ch9/unsubscribe")
            ->throw();

        return $response->json();
    }

    /**
     * Process the email.
     *
     * @param array $mobileNumbers
     * @return AutoProcessedEmailData
     */
    public function process(array $mobileNumbers): AutoProcessedEmailData
    {
        $autoProcessedEmailData = AutoProcessedEmailData::fromAutoProcessableEmailData($this->autoProcessableEmailData);
        $language = LanguageDectorService::ENGLISH_LANGUAGE;

        /**
         * No mobile numbers, send reply
         */
        if (empty($mobileNumbers)) {

            // Mobile number not found, prepare auto processed email data for forwarding.
            if ($this->wasAutoProcessed()) {
                return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
            }

            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND, $language));

            return $autoProcessedEmailData;
        }
        $autoProcessedEmailData->updatePotentialMobileNumbers($mobileNumbers);

        /**
         * Fetch subscriptions for each mobile number.
         */
        foreach ($mobileNumbers as $mobileNumber) {

            try {
                $subscription = $this->getSubscriptions($mobileNumber);
            } catch(RequestException $e) {
                if ($e->response->status() === 400) {
                    continue;
                }
                throw $e;
            }

            $status = $subscription['status'] ?? '';
            $unsubscribedAt = $subscription['unsubscribedAt'] ?? '';

            if (trim($status) === 'active' || empty($unsubscribedAt)) { 
                // Mobile number with active subscription.
                $autoProcessedEmailData->setHasActiveSubscription(true);
                $autoProcessedEmailData->updateMobileNumberWithActiveSubscription($mobileNumber);
            }

            $autoProcessedEmailData->setSubscriptionsResponse($mobileNumber, $subscription);
        }

        /**
         * No active subscription, send reply
         */
        $mobileNumbersWithActiveSubscription = $autoProcessedEmailData->getMobileNumbersWithActiveSubscription();
        if (empty($mobileNumbersWithActiveSubscription)) {

            // No active subscription, prepare auto processed email data for forwarding.
            if ($this->wasAutoProcessed()) {
                return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
            }

            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND, $language));

            return $autoProcessedEmailData;
        }

        /**
         * Unsubscribe from each subscription.
         */
        foreach ($mobileNumbersWithActiveSubscription as $mobileNumberWithActiveSubscription) {
            $unsubscribed = $this->unsubscribe($mobileNumberWithActiveSubscription);
            $active = $unsubscribed['success'] ?? '';

            if (trim($active) === 'true' || $active === true) {
                $autoProcessedEmailData->setWasUnsubscribed(true);
                $autoProcessedEmailData->updateUnsubscribedMobileNumbers($mobileNumberWithActiveSubscription);
            }

            $autoProcessedEmailData->setUnsubscribeResponse($mobileNumberWithActiveSubscription, $unsubscribed);
        }
        $unsubscribedMobileNumbers = $autoProcessedEmailData->getUnsubscribedMobileNumbers();

        /**
         * Failed to unsubscribe from any subscription.
         */
        if (!empty($mobileNumbersWithActiveSubscription) && empty($unsubscribedMobileNumbers)) {

            $autoProcessedEmailData->setProcessLog('api_error', 'Failed to unsubscribe from any subscription');

            return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
        }

        /**
         * Successfully unsubscribed from all subscriptions.
         */
        if (!empty($mobileNumbersWithActiveSubscription) && !empty($unsubscribedMobileNumbers)) {
            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::UNSUBSCRIBED);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::UNSUBSCRIBED, $language));

            return $autoProcessedEmailData;
        }

        $autoProcessedEmailData->setProcessLog('no_action_taken', true);

        return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
    }
}
