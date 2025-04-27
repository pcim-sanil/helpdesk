<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class SmsServiceShortCodeModel extends Model
{
    // Define the connection
    protected $connection = 'helpdesk';

    // Define the table name
    protected $table = 'sms_service_short_code';

    // Define the primary key
    protected $primaryKey = 'id';

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
        return $this->belongsTo(SmsServicesModel::class, 'sms_service_id', 'id');
    }

    /**
     * Belongs to Company Info through SMS Service
     * @return HasOneThrough
     */
    public function companyInfo(): HasOneThrough
    {
        return $this->hasOneThrough(CompanyInfoModel::class, SmsServicesModel::class, 'id', 'id', 'sms_service_id', 'company_info_id');
    }
}
