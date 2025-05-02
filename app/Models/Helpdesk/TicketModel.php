<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TicketModel extends Model
{

    public const TICKET_STATUS_OPEN = 0;
    public const TICKET_STATUS_CLOSED = 1;
    public const TICKET_STATUS_ESCLATE = 2;
    public const TICKET_STATUS_PENDING = 3;
    public const TICKET_STATUS_ESCLATE_TO_VENDOR = 4;

    // Define the connection
    protected $connection = 'helpdesk';

    // Define the table name
    protected $table = 'tickets';

    // Define the primary key
    protected $primaryKey = 'ticket_id';

    // Define the created_date column
    const CREATED_AT = 'created_date';

    // Define the updated_date column
    const UPDATED_AT = 'updated_date';

    /**
     * Belongs to SMS Service
     * @return BelongsTo
     */
    public function smsService(): BelongsTo
    {
        return $this->belongsTo(SmsServicesModel::class, 'service_1300', 'service_1300');
    }

    /**
     * Belongs to Company Info through SMS Service
     * @return HasOneThrough
     */
    public function companyInfo(): HasOneThrough
    {
        return $this->hasOneThrough(CompanyInfoModel::class, SmsServicesModel::class, 'service_1300', 'id', 'service_1300', 'company_info_id');
    }

    /**
     * Has one sms service short code
     * @return BelongsTo
     */
    public function smsServiceShortCode(): BelongsTo
    {
        return $this->belongsTo(SmsServiceShortCodeModel::class, 'enduser_short_code', 'short_code');
    }

    /**
     * Operator user who created or handles the ticket.
     * @return BelongsTo
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'operator_id', 'id');
    }
}