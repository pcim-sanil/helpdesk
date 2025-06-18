<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;

class CallerInfoModel extends Model
{
    // Define the connection
    protected $connection = 'helpdesk';

    // Define the table name
    protected $table = 'caller_info';

    // Define the primary key
    protected $primaryKey = 'id';

    // Define the created_date column
    const CREATED_AT = 'created_date';

    // Define the updated_date column
    const UPDATED_AT = 'updated_date';

    // Define fillable fields
    protected $fillable = [
        'enduser_mobile',
        'enduser_mobile_8',
        'enduser_name',
        'enduser_call_originate_number',
        'enduser_marketing_number',
        'enduser_unsubscribed',
        'cbd_caller_number',
    ];

    // Define casts for data types
    protected $casts = [
        'enduser_unsubscribed' => 'boolean',
        'cbd_caller_number' => 'integer',
    ];

    // Define default values
    protected $attributes = [
        'enduser_marketing_number' => '',
        'enduser_unsubscribed' => '0',
        'cbd_caller_number' => 0,
    ];
} 