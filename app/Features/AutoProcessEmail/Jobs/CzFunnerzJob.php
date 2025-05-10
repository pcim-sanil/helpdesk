<?php

namespace App\Features\AutoProcessEmail\Jobs;

use App\Features\AutoProcessEmail\Jobs\AutoProcessEmailJob;
use App\Services\LanguageDectorService;
use App\Features\AutoProcessEmail\AutoProcessResponseTypeEnum;
use App\Features\AutoProcessEmail\Data\AutoProcessedEmailData;
use Illuminate\Support\Facades\Http;

class CzFunnerzJob extends AutoProcessEmailJob
{
    private const BASE_URI = 'http://cl1.oddesseybizz.nl/gogogy/CZ2';
    private const TOKEN = '67890';


    public function getAllowedLanguages(): array
    {
        return [
            LanguageDectorService::CZECH_LANGUAGE,
            LanguageDectorService::CROATIAN_LANGUAGE,
            LanguageDectorService::ENGLISH_LANGUAGE
        ];
    }

    /**
     * POST http://cl1.oddesseybizz.nl/gogogy/CZ2/customercare_lookup.php?msisdn=00xxxx&token=67890
     * Sample response:{"active":true,"msisdn":"00420722109300","activated":"1745201345","stopped":"","operator":"23002","opt-out":"unsubscribe"},
     * Sample response:{"active":false,"msisdn":"00420736166731","activated":"1745202969","stopped":"1746429802","operator":"23003","opt-out":"unsubscribe"}}
     */
    public function getSubscriptions(string $mobileNumber): array
    {
        $response = Http::timeout($this->timeout)
            ->retry($this->tries, 100)
            ->withOptions([
                'query' => [
                    'msisdn' => $this->normalize($mobileNumber),
                    'token'  => self::TOKEN,
                ],
            ])
            ->post(self::BASE_URI . "/customercare_lookup.php")
            ->throw();

        return $response->json();
    }

    /**
     * POST http://cl1.oddesseybizz.nl/gogogy/CZ2/customercare_unsubscribe.php?msisdn=00xxxx&token=67890
     * Sample response:{"success":true,"msisdn":"00420704338661","activated":"1745207620","stopped":""}}
     */
    public function unsubscribe(string $mobileNumber): array
    {
        $response = Http::timeout($this->timeout)
            ->retry($this->tries, 100)
            ->withOptions([
                'query' => [
                    'msisdn' => $this->normalize($mobileNumber),
                    'token'  => self::TOKEN,
                ],
            ])
            ->post(self::BASE_URI . "/customercare_unsubscribe.php")
            ->throw();

        return $response->json();
    }

    public function getEmailTemplate(AutoProcessResponseTypeEnum $responseType, string $language = LanguageDectorService::ENGLISH_LANGUAGE): string
    {
        switch ($responseType) {
            case AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND:
                if (in_array($language, [LanguageDectorService::CZECH_LANGUAGE, LanguageDectorService::CROATIAN_LANGUAGE])) {
                    return 'mail.cs.mobile-number-not-found';
                }
                return 'mail.en.mobile-number-not-found';
            case AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND:
                if (in_array($language, [LanguageDectorService::CZECH_LANGUAGE, LanguageDectorService::CROATIAN_LANGUAGE])) {
                    return 'mail.cs.subscription-not-found';
                }
                return 'mail.en.subscription-not-found';
            case AutoProcessResponseTypeEnum::UNSUBSCRIBED:
                if (in_array($language, [LanguageDectorService::CZECH_LANGUAGE, LanguageDectorService::CROATIAN_LANGUAGE])) {
                    return 'mail.cs.unsubscribed';
                }
                return 'mail.en.unsubscribed';
            case AutoProcessResponseTypeEnum::CASE_FORWARDED:
                return 'mail.en.case-forwarded';
            default:
                return '';
        }
    }

    public function process(array $mobileNumbers): AutoProcessedEmailData
    {
        $autoProcessedEmailData = AutoProcessedEmailData::fromAutoProcessableEmailData($this->autoProcessableEmailData);

        /**
         * Detect language.
         */
        $language = $this->detectLanguage($this->autoProcessableEmailData->email_content);
        $autoProcessedEmailData->setProcessLog('detected_language', $language);
        $language = in_array($language, $this->getAllowedLanguages()) ? $language : LanguageDectorService::ENGLISH_LANGUAGE;
        $autoProcessedEmailData->setProcessLog('fallback_language', $language);


        /**
         * No mobile numbers, send reply
         */
        if (empty($mobileNumbers)) {
            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND, $language));

            return $autoProcessedEmailData;
        }
        $autoProcessedEmailData->updatePotentialMobileNumbers($mobileNumbers);

        /**
         * Fetch subscriptions for each mobile number.
         */
        foreach ($mobileNumbers as $mobileNumber) {
            $subscription = $this->getSubscriptions($mobileNumber);
            $active = $subscription['active'] ?? '';

            if (trim($active) === 'true' || $active === true) {
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
            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::CASE_FORWARDED);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::CASE_FORWARDED, $language));

            return $autoProcessedEmailData;
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

        return $autoProcessedEmailData;
    }
}
