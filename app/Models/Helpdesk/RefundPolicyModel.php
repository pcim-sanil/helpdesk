<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Helpdesk\CompanyInfoModel;
use App\Models\Helpdesk\SmsServicesModel;

class RefundPolicyModel extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'helpdesk';

    /**
     * The table name for the model.
     *
     * @var string
     */
    protected $table = 'refund_policy';

    /**
     * The created_date column for the model.
     *
     * @var string
     */
    const CREATED_AT = 'created_date';

    /**
     * The updated_date column for the model.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_date';

    /**
     * Belongs to company
     * 
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyInfoModel::class, 'company_id', 'id');
    }

    /**
     * Belongs to sms service
     * 
     * @return BelongsTo
     */
    public function smsService(): BelongsTo
    {
        return $this->belongsTo(SmsServicesModel::class, 'sms_services_id', 'id');
    }
}