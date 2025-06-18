<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $sender_name
 * @property string $sender_email
 * @property string $original_sender_email
 * @property string $receiver_email
 * @property string $cc
 * @property string $email_subject
 * @property string $email_content
 * @property string $email_attachment
 * @property string $email_received_date
 * @property int $status
 * @property int $special_emails_rule_status
 * @property string $report
 * @property int $generated_ticket_id
 * @property string $type (incoming, outgoing, draft)
 * @property int $email_queries_reply_templates_id
 * @property string $created_date
 * @property string $updated_date
 */
class QueryEmailModel extends Model
{
    protected $connection = 'helpdesk';

    protected $table = 'email_queries';

    const CREATED_AT = 'created_date';

    const UPDATED_AT = 'updated_date';

    public const STATUS_EMAIL_QUERY_RECEIVED = 1;

    public const STATUS_EMAIL_QUERY_TICKET_GENERATED = 2;

    public const STATUS_EMAIL_QUERY_REPLY_EMAIL_SENT = 3;

    public const STATUS_EMAIL_QUERY_REPLY_EMAIL_FAILED_TO_SENT = 4;

    public const TYPE_EMAIL_QUERY_INCOMING = 'incoming';

    public const TYPE_EMAIL_QUERY_OUTGOING = 'outgoing';

    public const TYPE_EMAIL_QUERY_DRAFT = 'draft';

    public function autoProcessedEmails(): HasMany
    {
        return $this->hasMany(AutoProcessedEmailModel::class, 'query_email_id', 'id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TicketModel::class, 'generated_ticket_id', 'ticket_id');
    }
}
