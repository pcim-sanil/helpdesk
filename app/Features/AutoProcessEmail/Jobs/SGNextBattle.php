<?php

namespace App\Features\AutoProcessEmail\Jobs;

use App\Features\AutoProcessEmail\Jobs\AutoProcessEmailJob;
use App\Services\LanguageDectorService;
use App\Features\AutoProcessEmail\AutoProcessResponseTypeEnum;
use App\Features\AutoProcessEmail\Data\AutoProcessedEmailData;
use App\Features\AutoProcessEmail\OddesseysmsTrait;

class SGNextBattle extends AutoProcessEmailJob
{
    use OddesseysmsTrait;

    /**
     * Manual process refund.
     *
     * @var bool
     */
    protected bool $manualProcessRefund = true;

    public function getBaseUri(): string
    {
        return 'https://oddesseysms.nl/sg1';
    }

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
