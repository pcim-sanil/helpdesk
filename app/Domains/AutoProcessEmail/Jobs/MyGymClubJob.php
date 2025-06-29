<?php

namespace App\Domains\AutoProcessEmail\Jobs;

use App\Domains\AutoProcessEmail\AutoProcessResponseTypeEnum;
use App\Domains\AutoProcessEmail\Data\AutoProcessedEmailData;
use App\Domains\AutoProcessEmail\OddesseysmsTrait;
use App\Services\LanguageDectorService;

/**
 * sms_service_id: 465
 * contract code: 2922
 * email: republicresponse.mygymclub.psms.pl@billingsupport-global.com
 */
class MyGymClubJob extends AutoProcessEmailJob
{
    use OddesseysmsTrait;

    protected bool $manualProcessRefund = false;

    public function getBaseUri(): string
    {
        return 'https://oddesseysms.nl/pl13';
    }

    /**
     * Get allowed languages.
     */
    public function getAllowedLanguages(): array
    {
        return [
            LanguageDectorService::POLISH_LANGUAGE,
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
                if (in_array($language, [LanguageDectorService::POLISH_LANGUAGE])) {
                    return 'mail.pl.465.mobile-number-not-found';
                }

                return 'mail.en.mobile-number-not-found';
            case AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND:
                if (in_array($language, [LanguageDectorService::POLISH_LANGUAGE])) {
                    return 'mail.pl.465.subscription-not-found';
                }

                return 'mail.en.subscription-not-found';
            case AutoProcessResponseTypeEnum::UNSUBSCRIBED:
                if (in_array($language, [LanguageDectorService::POLISH_LANGUAGE])) {
                    return 'mail.pl.465.unsubscribed';
                }

                return 'mail.en.unsubscribed';
            case AutoProcessResponseTypeEnum::REFUND_REQUESTED:
                if (in_array($language, [LanguageDectorService::POLISH_LANGUAGE])) {
                    return 'mail.pl.465.refund-request';
                }

                return 'mail.en.465.refund-request';

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
         * Detect intent.
         */
        $autoProcessedEmailData = $this->detectIntent($autoProcessedEmailData);

        /**
         * No mobile numbers, send reply
         */
        if (empty($mobileNumbers)) {

            // Mobile number not found, prepare auto processed email data for forwarding.
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
        if (!empty($mobileNumbersWithActiveSubscription)) {
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
        if(empty($mobileNumbersWithActiveSubscription) || !empty($unsubscribedMobileNumbers)) {

            if(in_array('refund', $autoProcessedEmailData->getIntents())) {
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
