<?php

namespace App\Features\AutoProcessEmail;

use App\Features\AutoProcessEmail\Data\AutoProcessableEmailData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AuroProcessEmailService
{
    /**
     * Get the emails that can be auto processed
     *
     * @param array $smsServicesIds
     * @param int $limit
     * @return Collection<AutoProcessableEmailData>
     */
    public function getEmailsToBeProcessed(int $limit = 100): Collection
    {
        $sql = "SELECT
                        eq.*,
                        eq.id as query_email_id,
                        (
                        SELECT
                            COUNT(*) AS nos
                        FROM
                            auto_processed_email as ape0
                        where
                            ape0.sender_email = eq.sender_email
                            AND ape0.sms_services_id = ss.id
                            AND ape0.response_type != 'MOBILE_NUMBER_NOT_FOUND') as no_of_auto_processed,
                        ss.id as sms_services_id,
                        ss.brand_name,
                        csc.query_email,
                        csc.query_email_password,
                        c.google_country as country_iso_alpha2,
                        apes.job_class,
                        apes.detect_languages,
                        apes.forward_to
                    FROM
                        email_queries AS eq
                    JOIN tickets AS t ON
                        t.ticket_id = eq.generated_ticket_id
                    JOIN sms_services AS ss ON
                        ss.service_1300 = t.service_1300
                    JOIN auto_process_email_services AS apes ON
                        apes.sms_services_id = ss.id
                    JOIN company_service_configuration csc on
                        csc.sms_services_id = ss.id
                    JOIN company_service_country as csc1 on
                        csc1.company_id = ss.company_info_id
                    JOIN 
                        countries as c on 
                        c.country_code  = csc1.country_code 
                WHERE
                        eq.type = 'incoming'
                        AND t.status = '0'
                        AND t.operator_id = '0'
                        and apes.auto_process_on_helpdesk = '1'
                        and eq.id not in (select ape1.query_email_id from auto_processed_email ape1)
                        and eq.sender_email not like 'MicrosoftExchange%' 
                LIMIT $limit";

        
        $results = DB::connection('helpdesk')->select($sql);

        return collect($results)->map(function ($result) {
            return new AutoProcessableEmailData(
                id: $result->id,
                query_email_id: $result->query_email_id,
                sender_name: $result->sender_name ?? null,
                sender_email: $result->sender_email ?? null,
                original_sender_email: $result->original_sender_email ?? null,
                receiver_email: $result->receiver_email ?? null,
                cc: $result->cc ?? null,
                email_subject: $result->email_subject ?? null,
                email_content: $result->email_content ?? null,
                email_attachment: $result->email_attachment ?? null,
                email_received_date: $result->email_received_date,
                status: $result->status ?? null,
                special_emails_rule_status: $result->special_emails_rule_status ?? null,
                report: $result->report ?? null,
                generated_ticket_id: $result->generated_ticket_id ?? null,
                type: $result->type ?? null,
                email_queries_reply_templates_id: $result->email_queries_reply_templates_id ?? null,
                created_date: $result->created_date ?? null,
                updated_date: $result->updated_date ?? null,
                no_of_auto_processed: $result->no_of_auto_processed ?? null,
                sms_services_id: $result->sms_services_id ?? null,
                brand_name: $result->brand_name ?? null,
                query_email: $result->query_email ?? null,
                query_email_password: $result->query_email_password ?? null,
                country_iso_alpha2: $result->country_iso_alpha2 ?? null,
                job_class: $result->job_class ?? null,
                detect_languages: $result->detect_languages ? explode(',', $result->detect_languages) : null,
                forward_to: $result->forward_to ? explode(',', $result->forward_to) : null,
            );
        });
    }
}
