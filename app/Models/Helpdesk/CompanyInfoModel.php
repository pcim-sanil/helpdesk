<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CompanyInfoModel extends Model
{
    // Define the connection
    protected $connection = 'helpdesk';

    // Define the table name
    protected $table = 'company_info';

    // Define the primary key
    protected $primaryKey = 'id';

    // Define the created_date column
    const CREATED_AT = 'created_date';

    // Define the updated_date column
    const UPDATED_AT = 'updated_date';

    /**
     * Has many SMS Services
     * @return HasMany
     */
    public function smsServices(): HasMany
    {
        return $this->hasMany(SmsServicesModel::class, 'company_info_id', 'id');
    }

    /**
     * Has many SMS Services Short Codes through SMS Services
     * @return HasManyThrough
     */
    public function smsServiceShortCodes(): HasManyThrough
    {
        return $this->hasManyThrough(
            SmsServiceShortCodeModel::class,
            SmsServicesModel::class,
            'company_info_id',
            'sms_service_id',
            'id',
            'id'
        );
    }
    
    /**
     * Has many Tickets through SMS Services
     * @return HasManyThrough
     */
    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(
            TicketModel::class,
            SmsServicesModel::class,
            'company_info_id',
            'service_1300',
            'id',
            'service_1300'
        );
    }
}