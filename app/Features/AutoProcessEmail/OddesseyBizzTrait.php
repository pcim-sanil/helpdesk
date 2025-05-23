<?php

namespace App\Features\AutoProcessEmail;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Features\AutoProcessEmail\Data\AutoProcessedEmailData;

trait OddesseyBizzTrait
{
    abstract public function getBaseUri(): string;
    abstract public function getToken(): string;

    /**
     * POST http://cl1.oddesseybizz.nl/gogogy/CZ2/customercare_lookup.php?msisdn=00xxxx&token=67890
     * Sample response:{"active":true,"msisdn":"00420722109300","activated":"1745201345","stopped":"","operator":"23002","opt-out":"unsubscribe"},
     * Sample response:{"active":false,"msisdn":"00420736166731","activated":"1745202969","stopped":"1746429802","operator":"23003","opt-out":"unsubscribe"}}
     */
    public function getSubscriptions(string $mobileNumber): array
    {
        try {
            $response = Http::timeout(300)
                ->retry(2, 100)
                ->withOptions([
                    'query' => [
                        'msisdn' => $this->normalize($mobileNumber),
                        'token'  => $this->getToken(),
                    ],
                ])
                ->post($this->getBaseUri() . "/customercare_lookup.php")
                ->throw();

            return $response->json();
        } catch (RequestException $e) {
            $status = $e->response->status();
            $body = $e->response?->json();

            if (is_array($body)) {
                return $body;
            }

            throw $e;
        }
    }

    public function unsubscribe(string $mobileNumber): array
    {
        try {
            $response = Http::timeout(300)
                ->retry(2, 100)
                ->withOptions([
                    'query' => [
                        'msisdn' => $this->normalize($mobileNumber),
                        'token'  => $this->getToken(),
                    ],
                ])
                ->post($this->getBaseUri() . "/customercare_unsubscribe.php")
                ->throw();

            return $response->json();
        } catch (RequestException $e) {
            $status = $e->response->status();
            $body = $e->response?->json();

            if (is_array($body)) {
                return $body;
            }

            throw $e;
        }
    }



    /**
     * Fetch subscriptions for mobile numbers.
     *
     * @param AutoProcessedEmailData $autoProcessedEmailData
     * @param array $mobileNumbers
     * @return AutoProcessedEmailData
     */
    public function fetchSubscriptionsForMobileNumbers(AutoProcessedEmailData $autoProcessedEmailData, array $mobileNumbers): AutoProcessedEmailData
    {
        /**
         * Fetch subscriptions for each mobile number.
         */
        foreach ($mobileNumbers as $mobileNumber) {
            $subscription = $this->getSubscriptions($mobileNumber);
            $active = $subscription['active'] ?? false;
            $activated = $subscription['activated'] ?? '';
            $stopped = $subscription['stopped'] ?? '';

            if (in_array($active, [true, 'true'], true)) {
                // Mobile number with active subscription.
                $autoProcessedEmailData->setHasActiveSubscription(true);
                $autoProcessedEmailData->updateMobileNumberWithActiveSubscription($mobileNumber);
            }

            $autoProcessedEmailData->setSubscriptionsResponse($mobileNumber, $subscription);
        }

        return $autoProcessedEmailData;
    }

    /**
     * Unsubscribe from mobile numbers.
     *
     * @param AutoProcessedEmailData $autoProcessedEmailData
     * @param array $mobileNumbers
     * @return AutoProcessedEmailData
     */
    public function unsubscribeFromMobileNumbers(AutoProcessedEmailData $autoProcessedEmailData, array $mobileNumbersWithActiveSubscription): AutoProcessedEmailData
    {
        foreach ($mobileNumbersWithActiveSubscription as $mobileNumberWithActiveSubscription) {
            $unsubscribed = $this->unsubscribe($mobileNumberWithActiveSubscription);
            $success = $unsubscribed['success'] ?? false;

            if (in_array($success, [true, 'true'], true)) {
                $autoProcessedEmailData->setWasUnsubscribed(true);
                $autoProcessedEmailData->updateUnsubscribedMobileNumbers($mobileNumberWithActiveSubscription);
            }

            $autoProcessedEmailData->setUnsubscribeResponse($mobileNumberWithActiveSubscription, $unsubscribed);
        }

        return $autoProcessedEmailData;
    }
}
