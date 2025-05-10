<?php

namespace App\Features\QueryEmail;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class QueryEmailService
{
    /**
     * Get the email chain by incoming email query ID
     * @param int $emailQueryId
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEmailChainByIncomingEmailQueryId(int $emailQueryId): Collection
    {
        $emailQuery = DB::connection('helpdesk')->table('email_queries as eq')
            ->select('eq.*', 'ss.id as sms_service_id')
            ->join('tickets as t', 't.ticket_id', '=', 'eq.generated_ticket_id')
            ->join('sms_services as ss', 'ss.service_1300', '=', 't.service_1300')
            ->where('eq.id', $emailQueryId)
            ->where('eq.type', 'incoming')
            ->first();

        if (!$emailQuery) {
            return collect();
        }

        $results = DB::connection('helpdesk')->table('email_queries as eq')
            ->select('eq.*')
            ->join('tickets as t', 't.ticket_id', '=', 'eq.generated_ticket_id')
            ->join('sms_services as ss', 'ss.service_1300', '=', 't.service_1300')
            ->where('ss.id', $emailQuery->sms_service_id)
            ->where(function ($query) use ($emailQuery) {
                $query->where('eq.sender_email', $emailQuery->sender_email)
                    ->orWhere('eq.receiver_email', $emailQuery->sender_email);
            })
            ->orderBy('eq.id', 'desc')
            ->limit(100)
            ->get();

        return $results;
    }
}
