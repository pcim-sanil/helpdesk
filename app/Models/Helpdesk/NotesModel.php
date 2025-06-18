<?php

namespace App\Models\Helpdesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotesModel extends Model
{
    // Define the connection
    protected $connection = 'helpdesk';

    // Define the table name
    protected $table = 'notes';

    // Define the primary key
    protected $primaryKey = 'id';

    // Define the created_date column
    const CREATED_AT = 'created_date';

    // Define the updated_date column
    const UPDATED_AT = 'updated_date';

    // Define fillable fields
    protected $fillable = [
        'ticket_id',
        'note',
        'created_by',
    ];

    /**
     * Belongs to Ticket
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(TicketModel::class, 'ticket_id', 'ticket_id');
    }

    /**
     * Belongs to User who created the note
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'created_by', 'id');
    }
}
