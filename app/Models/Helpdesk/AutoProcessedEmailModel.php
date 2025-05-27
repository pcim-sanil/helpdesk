<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoProcessedEmailModel extends Model
{
    protected $connection = 'helpdesk';

    protected $table = 'auto_processed_email';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'query_email_id',
        'sms_services_id',
        'sender_email',
        'processed_on',
        'forwarded',
        'response_type',
        'response_template_path',
        'has_active_subscription',
        'was_unsubscribed',
        'subscriptions_response',
        'unsubscribe_response',
        'reply_query_email_id',
        'mobile_numbers',
        'reply_email_sent',
        'intents',
        'forward_to',
        'process_log',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'query_email_id' => 'integer',
        'sms_services_id' => 'integer',
        'processed_on' => 'datetime',
        'forwarded' => 'boolean',
        'has_active_subscription' => 'boolean',
        'was_unsubscribed' => 'boolean',
        'reply_query_email_id' => 'integer',
        'mobile_numbers' => 'json',
        'reply_email_sent' => 'boolean',
        'intents' => 'json',
        'subscriptions_response' => 'json',
        'unsubscribe_response' => 'json',
        'forward_to' => 'json',
        'process_log' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function smsService(): BelongsTo
    {
        return $this->belongsTo(SmsServicesModel::class, 'sms_services_id', 'id');
    }

    public function queryEmail(): BelongsTo
    {
        return $this->belongsTo(QueryEmailModel::class, 'query_email_id', 'id');
    }

    public function replyQueryEmail(): BelongsTo
    {
        return $this->belongsTo(QueryEmailModel::class, 'reply_query_email_id', 'id');
    }

    /**
     * Get the unsubscribed mobile numbers. //unsubscribedForMobileNumbers
     * {"potentialMobileNumbers":["+420775098204","+420722109300","+420736166731"],"activeSubscriptionsForMobileNumbers":["+420775098204"],"unsubscribedForMobileNumbers":["+420775098204"]}
     */
    public function getUnsubscribedMobileNumbers(): array
    {
        if (empty($this->mobile_numbers['unsubscribedForMobileNumbers']) || ! is_array($this->mobile_numbers['unsubscribedForMobileNumbers'])) {
            return [];
        }

        return $this->mobile_numbers['unsubscribedForMobileNumbers'];
    }
}
