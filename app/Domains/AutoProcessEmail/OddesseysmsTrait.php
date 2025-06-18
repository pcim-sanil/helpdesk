<?php

namespace App\Features\AutoProcessEmail;

use App\Features\AutoProcessEmail\Data\AutoProcessedEmailData;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

trait OddesseysmsTrait
{
    abstract public function getBaseUri(): string;

    public function getSubscriptions(string $mobileNumber): array
    {
        try {
            $response = Http::timeout(300)
                ->retry(2, 100)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post('https://portal.telcosupport.com/phpinfo.php', [
                    'token' => 'nakuit',
                    'endpoint' => $this->getBaseUri().'/lookup?msisdn='.$this->normalize($mobileNumber),
                    'method' => 'POST',
                ])
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
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post('https://portal.telcosupport.com/phpinfo.php', [
                    'token' => 'nakuit',
                    'endpoint' => $this->getBaseUri().'/unsubscribe?msisdn='.$this->normalize($mobileNumber),
                    'method' => 'POST',
                ])
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
     */
    public function fetchSubscriptionsForMobileNumbers(AutoProcessedEmailData $autoProcessedEmailData, array $mobileNumbers): AutoProcessedEmailData
    {
        /**
         * Fetch subscriptions for each mobile number.
         */
        foreach ($mobileNumbers as $mobileNumber) {
            $subscription = $this->getSubscriptions($mobileNumber);
            $success = $subscription['success'] ?? false;
            $status = $subscription['status'] ?? '';
            $error = $subscription['error'] ?? '';
            $activatedAt = $subscription['activatedAt'] ?? '';
            $unsubscribedAt = $subscription['unsubscribedAt'] ?? '';

            if (in_array($success, [true, 'true'], true) && (trim($status) === 'active')) {
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
     */
    public function unsubscribeFromMobileNumbers(AutoProcessedEmailData $autoProcessedEmailData, array $mobileNumbersWithActiveSubscription): AutoProcessedEmailData
    {
        foreach ($mobileNumbersWithActiveSubscription as $mobileNumberWithActiveSubscription) {
            $unsubscribed = $this->unsubscribe($mobileNumberWithActiveSubscription);

            $success = $unsubscribed['success'] ?? false;
            $error = $unsubscribed['error'] ?? null;

            if (in_array($success, [true, 'true'], true)) {
                $autoProcessedEmailData->setWasUnsubscribed(true);
                $autoProcessedEmailData->updateUnsubscribedMobileNumbers($mobileNumberWithActiveSubscription);
            }

            $autoProcessedEmailData->setUnsubscribeResponse($mobileNumberWithActiveSubscription, $unsubscribed);
        }

        return $autoProcessedEmailData;
    }
}
