<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsServicesModel extends Model
{
    // Define the connection
    protected $connection = 'helpdesk';

    // Define the table name
    protected $table = 'sms_services';

    // Define the primary key
    protected $primaryKey = 'id';

    // Define the created_date column
    const CREATED_AT = 'created_date';

    // Define the updated_date column
    const UPDATED_AT = 'updated_date';

    /**
     * Belongs to Company Info
     */
    public function companyInfo(): BelongsTo
    {
        return $this->belongsTo(CompanyInfoModel::class, 'company_info_id', 'id');
    }

    /**
     * Has many Tickets
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TicketModel::class, 'service_1300', 'service_1300');
    }

    /**
     * Has many SMS Service Short Codes
     */
    public function smsServiceShortCodes(): HasMany
    {
        return $this->hasMany(SmsServiceShortCodeModel::class, 'sms_service_id', 'id');
    }
}
