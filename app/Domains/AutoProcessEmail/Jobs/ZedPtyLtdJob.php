<?php

namespace App\Domains\AutoProcessEmail\Jobs;

use App\Domains\AutoProcessEmail\AutoProcessResponseTypeEnum;
use App\Domains\AutoProcessEmail\Data\AutoProcessedEmailData;
use App\Domains\AutoProcessEmail\ZedPtyLtdTrait;
use App\Services\LanguageDectorService;

/**
 * sms_service_id: 477
 * contract code: 2934
 * company_info_id: 309 (Newry Global Media BH)
 * email: newry.globalmedia.dcb.bh@billingsupport-global.com
 */
class ZedPtyLtdJob extends AutoProcessEmailJob
{
    use ZedPtyLtdTrait;

    protected bool $manualProcessRefund = false;

    /**
     * Billing params for Newry Global Media (BH) / Superfunbox.
     *
     * Zain: 42602, STC: 42604, Batelco: 42601
     * Unsubscription via API is available for all carriers.
     * Refund via API is only available for Zain.
     *
     * @return array{countryCode: string, carrierCode: int|array<int>, productCode: int, supportUnsub: bool}
     */
    public function getBillingParams(): array
    {
        return [
            'countryCode' => 'B67',
            'carrierCode' => [42602, 42604, 42601], // Zain, STC, Batelco
            'productCode' => 1,
            'supportUnsub' => true,
        ];
    }

    /**
     * Get allowed languages.
     */
    public function getAllowedLanguages(): array
    {
        return [
            LanguageDectorService::ARABIC_LANGUAGE,
            LanguageDectorService::ENGLISH_LANGUAGE,
        ];
    }

    /**
     * Get the email template.
     */
    protected function getEmailTemplate(AutoProcessResponseTypeEnum $responseType, string $language = LanguageDectorService::ENGLISH_LANGUAGE): string
    {
        $prefix = in_array($language, [LanguageDectorService::ARABIC_LANGUAGE], true) ? 'mail.ar' : 'mail.en.477';

        switch ($responseType) {
            case AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND:
                return $prefix.'.mobile-number-not-found';
            case AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND:
                return $prefix.'.subscription-not-found';
            case AutoProcessResponseTypeEnum::UNSUBSCRIBED:
                return $prefix.'.unsubscribed';
            case AutoProcessResponseTypeEnum::REFUND_REQUESTED:
                return $prefix.'.refund-request';
            case AutoProcessResponseTypeEnum::CASE_FORWARDED:
                return $prefix.'.case-forwarded';
            default:
                return $prefix.'.unsubscribed';
        }
    }

    /**
     * Process the email.
     */
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
         * Detect intent.
         */
        $autoProcessedEmailData = $this->detectIntent($autoProcessedEmailData);

        /**
         * No mobile numbers, send reply
         */
        if (empty($mobileNumbers)) {

            if ($this->wasAutoProcessed() && in_array('refund', $autoProcessedEmailData->getIntents())) {
                $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::REFUND_REQUESTED);
                $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::REFUND_REQUESTED, $language));

                return $autoProcessedEmailData;
            }

            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND, $language));

            return $autoProcessedEmailData;
        }
        $autoProcessedEmailData->updatePotentialMobileNumbers($mobileNumbers);

        /**
         * Fetch subscriptions for each mobile number.
         */
        $autoProcessedEmailData = $this->fetchSubscriptionsForMobileNumbers($autoProcessedEmailData, $mobileNumbers);

        /**
         * No active subscription, send reply
         */
        $mobileNumbersWithActiveSubscription = $autoProcessedEmailData->getMobileNumbersWithActiveSubscription();
        $neverSubscribed = $autoProcessedEmailData->getNeverSubscribed();

        if ($neverSubscribed) {
            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND, $language));

            return $autoProcessedEmailData;
        }

        /**
         * Unsubscribe from each subscription.
         */
        $unsubscribedMobileNumbers = [];
        if (! empty($mobileNumbersWithActiveSubscription)) {
            $autoProcessedEmailData = $this->unsubscribeFromMobileNumbers($autoProcessedEmailData, $mobileNumbersWithActiveSubscription);

            $unsubscribedMobileNumbers = $autoProcessedEmailData->getUnsubscribedMobileNumbers();

            /**
             * Failed to unsubscribe from any subscription.
             */
            if (empty($unsubscribedMobileNumbers)) {
                $autoProcessedEmailData->setProcessLog('api_error', 'Failed to unsubscribe from any subscription');

                return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
            }
        }

        /**
         * If no active subscription or unsubscribed, send reply unsubscribed.
         */
        if (empty($mobileNumbersWithActiveSubscription) || ! empty($unsubscribedMobileNumbers)) {

            if (in_array('refund', $autoProcessedEmailData->getIntents())) {
                $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::REFUND_REQUESTED);
                $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::REFUND_REQUESTED, $language));

                return $autoProcessedEmailData;
            }

            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::UNSUBSCRIBED);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::UNSUBSCRIBED, $language));

            return $autoProcessedEmailData;
        }

        $autoProcessedEmailData->setProcessLog('no_action_taken', true);

        return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
    }
}
