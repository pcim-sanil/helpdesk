<?php

namespace App\Domains\AutoProcessEmail\Data;

use App\Domains\AutoProcessEmail\AutoProcessResponseTypeEnum;
use Spatie\LaravelData\Data;

class AutoProcessedEmailData extends Data
{
    public function __construct(
        public int $query_email_id,
        public int $sms_services_id,
        public string $sender_email,
        public ?bool $forwarded = null,
        public ?AutoProcessResponseTypeEnum $response_type = null,
        public ?string $response_template_path = null,
        public ?bool $has_active_subscription = null,
        public ?bool $was_unsubscribed = null,
        public array $subscriptions_response = [],
        public array $unsubscribe_response = [],
        public ?int $reply_query_email_id = null,
        public array $mobile_numbers = [],
        public array $forward_to = [],
        public array $process_log = [],
        public ?bool $reply_email_sent = null,
        public array $intents = [],
        public ?bool $never_subscribed = null,
    ) {}

    public static function fromAutoProcessableEmailData(AutoProcessableEmailData $autoProcessableEmailData): self
    {
        return new self(
            query_email_id: $autoProcessableEmailData->query_email_id,
            sms_services_id: $autoProcessableEmailData->sms_services_id,
            sender_email: $autoProcessableEmailData->sender_email
        );
    }

    public function updatePotentialMobileNumbers(array $potentialMobileNumbers): void
    {
        $this->mobile_numbers['potentialMobileNumbers'] = $potentialMobileNumbers;
    }

    public function getPotentialMobileNumbers(): array
    {
        return $this->mobile_numbers['potentialMobileNumbers'] ?? [];
    }

    public function updateMobileNumberWithNoActiveSubscription(string $mobileNumbersWithSubscriptions): void
    {
        $this->mobile_numbers['noActiveSubscriptionsForMobileNumbers'][] = $mobileNumbersWithSubscriptions;
    }

    public function getMobileNumbersWithNoActiveSubscription(): array
    {
        return $this->mobile_numbers['noActiveSubscriptionsForMobileNumbers'] ?? [];
    }

    public function updateMobileNumberWithActiveSubscription(string $mobileNumbersWithSubscriptions): void
    {
        $this->mobile_numbers['activeSubscriptionsForMobileNumbers'][] = $mobileNumbersWithSubscriptions;
    }

    public function getMobileNumbersWithActiveSubscription(): array
    {
        return $this->mobile_numbers['activeSubscriptionsForMobileNumbers'] ?? [];
    }

    public function updateMobileNumberWithInactiveSubscription(string $mobileNumbersWithSubscriptions): void
    {
        $this->mobile_numbers['inactiveSubscriptionsForMobileNumbers'][] = $mobileNumbersWithSubscriptions;
    }

    public function getMobileNumbersWithInactiveSubscription(): array
    {
        return $this->mobile_numbers['inactiveSubscriptionsForMobileNumbers'] ?? [];
    }

    public function updateUnsubscribedMobileNumbers(string $mobileNumbersWithSubscriptions): void
    {
        $this->mobile_numbers['unsubscribedForMobileNumbers'][] = $mobileNumbersWithSubscriptions;
    }

    public function getUnsubscribedMobileNumbers(): array
    {
        return $this->mobile_numbers['unsubscribedForMobileNumbers'] ?? [];
    }

    public function setForwarded(bool $forwarded): void
    {
        $this->forwarded = $forwarded;
    }

    public function setResponseType(AutoProcessResponseTypeEnum $responseType): void
    {
        $this->response_type = $responseType;
    }

    public function setResponseTemplatePath(?string $responseTemplatePath): void
    {
        $this->response_template_path = $responseTemplatePath;
    }

    public function setHasActiveSubscription(bool $hasActiveSubscription): void
    {
        $this->has_active_subscription = $hasActiveSubscription;
    }

    public function setWasUnsubscribed(bool $wasUnsubscribed): void
    {
        $this->was_unsubscribed = $wasUnsubscribed;
    }

    public function setSubscriptionsResponse(string $mobileNumber, mixed $subscriptionsResponse): void
    {
        $this->subscriptions_response[$mobileNumber] = $subscriptionsResponse;
    }

    public function setUnsubscribeResponse(string $mobileNumber, mixed $unsubscribeResponse): void
    {
        $this->unsubscribe_response[$mobileNumber] = $unsubscribeResponse;
    }

    public function setReplyQueryEmailId(int $replyQueryEmailId): void
    {
        $this->reply_query_email_id = $replyQueryEmailId;
    }

    public function setForwardTo(array $forwardTo): void
    {
        $this->forward_to = $forwardTo;
    }

    public function setProcessLog(string $key, mixed $value): void
    {
        $this->process_log[$key] = $value;
    }

    public function getProcessLog(): array
    {
        return $this->process_log;
    }

    public function setReplyEmailSent(bool $replyEmailSent): void
    {
        $this->reply_email_sent = $replyEmailSent;
    }

    public function getReplyEmailSent(): ?bool
    {
        return $this->reply_email_sent;
    }

    public function setIntents(array $intents): void
    {
        $this->intents = $intents;
    }

    public function getIntents(): array
    {
        return $this->intents;
    }
    
    public function setNeverSubscribed(bool $neverSubscribed): void
    {
        $this->never_subscribed = $neverSubscribed;
    }

    public function getNeverSubscribed(): ?bool
    {
        return $this->never_subscribed;
    }
}
