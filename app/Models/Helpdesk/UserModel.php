<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserModel extends Model
{
    // Define the connection
    protected $connection = 'helpdesk';

    // Define the table name
    protected $table = 'users';

    // Define the primary key
    protected $primaryKey = 'id';

    // does not have created_at or updated_at
    public $timestamps = false;

    protected $casts = [
        'autotimestamp' => 'datetime',
        'last_login' => 'datetime',
        'last_active' => 'datetime',
        'contract_start_date' => 'datetime',
    ];

    /**
     * Belongs to Company Info
     */
    public function companyInfo(): BelongsTo
    {
        return $this->belongsTo(CompanyInfoModel::class, 'company_id', 'id');
    }

    /**
     * Has many Tickets
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(TicketModel::class, 'operator_id', 'id');
    }
}
