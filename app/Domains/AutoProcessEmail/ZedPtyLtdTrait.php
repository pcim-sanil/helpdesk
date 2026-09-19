<?php

namespace App\Domains\AutoProcessEmail;

use App\Domains\AutoProcessEmail\Data\AutoProcessedEmailData;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait ZedPtyLtdTrait
{
    private const API_BASE_URI = 'https://genericbilling.zed.com/api/v1/';

    private const ACCESS_KEY = 'dheUHba4W4';

    private const ZED_PARTNER = '7a4f308d-661f-4596-a386-8ea5c46f3089';

    /**
     * Billing params for this market.
     *
     * @return array{countryCode: string, carrierCode: int|array<int>, productCode: int, supportUnsub?: bool, supportUbsub?: bool}
     */
    abstract public function getBillingParams(): array;

    /**
     * Whether unsubscribe is supported for this market.
     */
    protected function supportsUnsub(): bool
    {
        $billing = $this->getBillingParams();

        return ! empty($billing['supportUnsub']) || ! empty($billing['supportUbsub']);
    }

    /**
     * POST to Zed Generic Billing API.
     *
     * @return array{ok: bool, body?: mixed, error?: string, httpCode?: int}
     */
    protected function postJson(string $path, array $payload): array
    {
        $url = self::API_BASE_URI.ltrim($path, '/');
        $jsonBody = json_encode($payload);

        try {
            $response = Http::timeout(60)
                ->retry(2, 100)
                ->withHeaders([
                    'accept' => '*/*',
                    'access-key' => self::ACCESS_KEY,
                    'zed-partner' => self::ZED_PARTNER,
                    'Content-Type' => 'application/json-patch+json',
                ])
                ->withBody($jsonBody, 'application/json-patch+json')
                ->post($url);

            $httpCode = $response->status();
            $body = $response->json() ?? $response->body();

            if ($response->successful()) {
                return ['ok' => true, 'body' => $body, 'httpCode' => $httpCode];
            }

            $errMsg = is_array($body) ? json_encode($body) : (string) $body;

            return ['ok' => false, 'error' => $errMsg ?: 'API error', 'body' => $body, 'httpCode' => $httpCode];
        } catch (RequestException $e) {
            $status = $e->response?->status() ?? 0;
            $body = $e->response?->json() ?? $e->response?->body();
            $errMsg = is_array($body) ? json_encode($body) : (string) ($body ?: $e->getMessage());

            return ['ok' => false, 'error' => $errMsg ?: 'API error', 'body' => $body, 'httpCode' => $status];
        } catch (\Throwable $e) {
            Log::channel('email_processing')->error('ZedPtyLtd API request failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return ['ok' => false, 'error' => $e->getMessage(), 'httpCode' => 0];
        }
    }

    /**
     * Whether a subscription is considered active.
     */
    protected function isSubscriptionActive(array $sub): bool
    {
        $code = (int) ($sub['SubscriptionCode'] ?? 0);
        if ($code <= 0) {
            return false;
        }

        $end = $sub['EndDate']['When'] ?? '';

        if ($end === '' || $end === null) {
            return true;
        }

        try {
            return Carbon::parse($end)->isFuture();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Fetch subscriptions for an MSISDN across all configured carriers.
     *
     * @return array{
     *     ok: bool,
     *     msisdn: string,
     *     byCarrier: array,
     *     hasActive: bool,
     *     hasInactive: bool,
     *     activeCarrierCodes: array<int>,
     *     supportUnsub: bool,
     *     error?: string
     * }
     */
    public function getSubscriptions(string $mobileNumber): array
    {
        $billing = $this->getBillingParams();
        $msisdn = $this->normalize($mobileNumber);

        if ($msisdn === '' || empty($billing['countryCode']) || empty($billing['productCode'])) {
            return [
                'ok' => false,
                'msisdn' => $msisdn,
                'byCarrier' => [],
                'hasActive' => false,
                'hasInactive' => false,
                'activeCarrierCodes' => [],
                'supportUnsub' => $this->supportsUnsub(),
                'error' => 'Missing billing parameters or MSISDN.',
            ];
        }

        $carrierCodes = is_array($billing['carrierCode'])
            ? $billing['carrierCode']
            : [$billing['carrierCode']];

        if (empty($carrierCodes)) {
            return [
                'ok' => false,
                'msisdn' => $msisdn,
                'byCarrier' => [],
                'hasActive' => false,
                'hasInactive' => false,
                'activeCarrierCodes' => [],
                'supportUnsub' => $this->supportsUnsub(),
                'error' => 'No carrier codes configured.',
            ];
        }

        $byCarrier = [];
        $activeCarrierCodes = [];
        $hasActive = false;
        $hasInactive = false;
        $errors = [];
        $totalCount = 0;

        foreach ($carrierCodes as $carrierCode) {
            $carrierCode = (int) $carrierCode;

            $decoded = $this->postJson('Subscriptions', [
                'msisdn' => $msisdn,
                'countryCode' => $billing['countryCode'],
                'carrierCode' => $carrierCode,
                'productCode' => (int) $billing['productCode'],
            ]);

            $entry = [
                'carrierCode' => $carrierCode,
                'productCode' => (int) $billing['productCode'],
                'countryCode' => $billing['countryCode'],
                'ok' => false,
                'httpCode' => (int) ($decoded['httpCode'] ?? 0),
                'subscriptions' => [],
                'error' => null,
            ];

            if (empty($decoded['ok'])) {
                $errMsg = $decoded['error'] ?? 'Unknown error';
                $entry['error'] = $errMsg;
                $errors[] = 'carrierCode '.$carrierCode.': '.$errMsg;
                $byCarrier[$carrierCode] = $entry;

                continue;
            }

            $body = $decoded['body'] ?? [];
            $subs = (is_array($body) && isset($body['Subscriptions']) && is_array($body['Subscriptions']))
                ? $body['Subscriptions']
                : [];

            foreach ($subs as &$sub) {
                if (is_array($sub) && ! isset($sub['CarrierCode'])) {
                    $sub['CarrierCode'] = $carrierCode;
                }

                if (is_array($sub) && $this->isSubscriptionActive($sub)) {
                    $hasActive = true;
                    $activeCarrierCodes[] = $carrierCode;
                } elseif (is_array($sub) && (int) ($sub['SubscriptionCode'] ?? 0) > 0) {
                    $hasInactive = true;
                }
            }
            unset($sub);

            $entry['ok'] = true;
            $entry['subscriptions'] = $subs;
            $byCarrier[$carrierCode] = $entry;
            $totalCount += count($subs);
        }

        $result = [
            'ok' => empty($errors) || $totalCount > 0,
            'msisdn' => $msisdn,
            'byCarrier' => $byCarrier,
            'hasActive' => $hasActive,
            'hasInactive' => $hasInactive,
            'activeCarrierCodes' => array_values(array_unique($activeCarrierCodes)),
            'supportUnsub' => $this->supportsUnsub(),
        ];

        if (! empty($errors)) {
            $result['error'] = implode(' | ', $errors);
            if ($totalCount === 0) {
                $result['ok'] = false;
            }
        }

        return $result;
    }

    /**
     * Unsubscribe an MSISDN from a carrier/product.
     *
     * @return array{ok: bool, error?: string, body?: mixed, httpCode?: int}
     */
    public function unsubscribe(string $mobileNumber, int $carrierCode): array
    {
        if (! $this->supportsUnsub()) {
            return ['ok' => false, 'error' => 'Unsubscribe is not enabled for this company.'];
        }

        $billing = $this->getBillingParams();
        $msisdn = $this->normalize($mobileNumber);

        if ($msisdn === '') {
            return ['ok' => false, 'error' => 'No valid MSISDN.'];
        }

        $allowedCarriers = is_array($billing['carrierCode'])
            ? array_map('intval', $billing['carrierCode'])
            : [(int) $billing['carrierCode']];

        if (! in_array($carrierCode, $allowedCarriers, true)) {
            return ['ok' => false, 'error' => 'Carrier code not allowed for this company.'];
        }

        return $this->postJson('Unsubscribe', [
            'msisdn' => $msisdn,
            'countryCode' => $billing['countryCode'],
            'carrierCode' => $carrierCode,
            'productCode' => (int) $billing['productCode'],
        ]);
    }

    /**
     * Fetch subscriptions for mobile numbers.
     */
    public function fetchSubscriptionsForMobileNumbers(AutoProcessedEmailData $autoProcessedEmailData, array $mobileNumbers): AutoProcessedEmailData
    {
        foreach ($mobileNumbers as $mobileNumber) {
            $subscription = $this->getSubscriptions($mobileNumber);

            if (! empty($subscription['hasActive'])) {
                $autoProcessedEmailData->setHasActiveSubscription(true);
                $autoProcessedEmailData->updateMobileNumberWithActiveSubscription($mobileNumber);
                $autoProcessedEmailData->setNeverSubscribed(false);
            } elseif (! empty($subscription['hasInactive'])) {
                $autoProcessedEmailData->setHasActiveSubscription(false);
                $autoProcessedEmailData->updateMobileNumberWithInactiveSubscription($mobileNumber);
                $autoProcessedEmailData->setNeverSubscribed(false);
            } else {
                $autoProcessedEmailData->setHasActiveSubscription(false);
                $autoProcessedEmailData->setNeverSubscribed(true);
            }

            $autoProcessedEmailData->setSubscriptionsResponse($mobileNumber, $subscription);
        }

        return $autoProcessedEmailData;
    }

    /**
     * Unsubscribe from mobile numbers (per active carrier).
     */
    public function unsubscribeFromMobileNumbers(AutoProcessedEmailData $autoProcessedEmailData, array $mobileNumbersWithActiveSubscription): AutoProcessedEmailData
    {
        foreach ($mobileNumbersWithActiveSubscription as $mobileNumberWithActiveSubscription) {
            $subscription = $autoProcessedEmailData->subscriptions_response[$mobileNumberWithActiveSubscription] ?? [];
            $carrierCodes = $subscription['activeCarrierCodes'] ?? [];
            $responses = [];
            $anySuccess = false;

            if (empty($subscription['supportUnsub'])) {
                $autoProcessedEmailData->setUnsubscribeResponse($mobileNumberWithActiveSubscription, [
                    'ok' => false,
                    'error' => 'Unsubscribe is not enabled for this company.',
                ]);

                continue;
            }

            foreach ($carrierCodes as $carrierCode) {
                $unsubscribed = $this->unsubscribe($mobileNumberWithActiveSubscription, (int) $carrierCode);
                $responses[(int) $carrierCode] = $unsubscribed;

                if (! empty($unsubscribed['ok'])) {
                    $anySuccess = true;
                }
            }

            if ($anySuccess) {
                $autoProcessedEmailData->setWasUnsubscribed(true);
                $autoProcessedEmailData->updateUnsubscribedMobileNumbers($mobileNumberWithActiveSubscription);
            }

            $autoProcessedEmailData->setUnsubscribeResponse($mobileNumberWithActiveSubscription, $responses);
        }

        return $autoProcessedEmailData;
    }
}
