<?php

namespace App\Domains\AutoProcessEmail\Jobs;

use App\Domains\AutoProcessEmail\AutoProcessResponseTypeEnum;
use App\Domains\AutoProcessEmail\Data\AutoProcessedEmailData;
use App\Domains\AutoProcessEmail\OddesseysmsTrait;
use App\Services\LanguageDectorService;

class NOMagzter extends AutoProcessEmailJob
{
    use OddesseysmsTrait;

    public function getBaseUri(): string
    {
        return 'https://oddesseysms.nl/no1';
    }

    /**
     * Get allowed languages.
     */
    public function getAllowedLanguages(): array
    {
        return [
            LanguageDectorService::NORWEGIAN_LANGUAGE,
            LanguageDectorService::DANISH_LANGUAGE,
            LanguageDectorService::SWEDISH_LANGUAGE,
            LanguageDectorService::ENGLISH_LANGUAGE,
        ];
    }

    /**
     * Get the email template.
     */
    protected function getEmailTemplate(AutoProcessResponseTypeEnum $responseType, string $language = LanguageDectorService::ENGLISH_LANGUAGE): string
    {
        switch ($responseType) {
            case AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND:
                // czech and croatian languages are similar so AI may detect it as czech or croatian so let's use the same template for both
                if (in_array($language, [LanguageDectorService::NORWEGIAN_LANGUAGE, LanguageDectorService::DANISH_LANGUAGE, LanguageDectorService::SWEDISH_LANGUAGE])) {
                    return 'mail.no.mobile-number-not-found';
                }

                return 'mail.en.mobile-number-not-found';
            case AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND:
                if (in_array($language, [LanguageDectorService::NORWEGIAN_LANGUAGE, LanguageDectorService::DANISH_LANGUAGE, LanguageDectorService::SWEDISH_LANGUAGE])) {
                    return 'mail.no.subscription-not-found';
                }

                return 'mail.en.subscription-not-found';
            case AutoProcessResponseTypeEnum::UNSUBSCRIBED:
                if (in_array($language, [LanguageDectorService::NORWEGIAN_LANGUAGE, LanguageDectorService::DANISH_LANGUAGE, LanguageDectorService::SWEDISH_LANGUAGE])) {
                    return 'mail.no.unsubscribed';
                }

                return 'mail.en.unsubscribed';
            case AutoProcessResponseTypeEnum::CASE_FORWARDED:
                return 'mail.en.case-forwarded';
            default:
                return '';
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
        $autoProcessedEmailData = $this->fetchSubscriptionsForMobileNumbers($autoProcessedEmailData, $mobileNumbers);

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
        $autoProcessedEmailData = $this->unsubscribeFromMobileNumbers($autoProcessedEmailData, $mobileNumbersWithActiveSubscription);

        $unsubscribedMobileNumbers = $autoProcessedEmailData->getUnsubscribedMobileNumbers();

        /**
         * Failed to unsubscribe from any subscription.
         */
        if (! empty($mobileNumbersWithActiveSubscription) && empty($unsubscribedMobileNumbers)) {

            $autoProcessedEmailData->setProcessLog('api_error', 'Failed to unsubscribe from any subscription');

            return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
        }

        /**
         * Successfully unsubscribed from all subscriptions.
         */
        if (! empty($mobileNumbersWithActiveSubscription) && ! empty($unsubscribedMobileNumbers)) {
            $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::UNSUBSCRIBED);
            $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::UNSUBSCRIBED, $language));

            return $autoProcessedEmailData;
        }

        $autoProcessedEmailData->setProcessLog('no_action_taken', true);

        return $this->prepareAutoProcessedEmailDataForForwarding($autoProcessedEmailData);
    }
}
