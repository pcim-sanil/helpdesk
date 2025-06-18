<?php

namespace App\Domains\Ticket;

use Illuminate\Support\Facades\DB;
use App\Domains\Ticket\Data\TicketDetailData;

class TicketService
{
    /**
     * Get the status options
     */
    public static function getStatusOptions(): array
    {
        return [
            '0' => 'Open',
            '1' => 'Closed',
            '2' => 'Escalated',
            '3' => 'Pending',
            '4' => 'Escalated to Vendor',
        ];
    }

    /**
     * Get the enquiry type options
     */
    public static function getEnquiryTypeOptions(): array
    {
        return [
            '0' => 'Unsub 19 SMS',
            '1' => 'Unsub 04 Marketing',
            '2' => 'Incoming Call',
            '3' => 'Call Back',
            '4' => 'Answered',
            '5' => 'In Call',
            '6' => 'Manual Ticket',
            '7' => 'Refund Email',
            '8' => 'Email Ticket',
        ];
    }

    /**
     * Get the label for the enquiry type
     */
    public static function getEnquiryTypeLabel(int $enqueryType): string
    {
        $enquiryTypeLabels = self::getEnquiryTypeOptions();

        return $enquiryTypeLabels[$enqueryType] ?? 'Unknown';
    }

    /**
     * Get the user info for the ticket
     *
     * @param int $ticketId
     * @return TicketDetailData
     */
    public static function getTicketDetails(int $ticketId): TicketDetailData
    {
        $sql = "SELECT
                    tickets.caller_info_mobile AS caller_mobile,
                    tickets.caller_email AS caller_email,
                    tickets.callback_number AS callback_number,
                    tickets.created_date AS created_date,
                    (
                        SELECT ci.enduser_name
                        FROM caller_info ci
                        WHERE ci.enduser_mobile_8 = tickets.caller_info_mobile_8
                        ORDER BY ci.updated_date DESC
                        LIMIT 1
                    ) AS caller_name,
                    tickets.service_1300,
                    tickets.enquiry_type,
                    tickets.reassigned_from,
                    company_info.id AS company_id,
                    company_info.company_name,
                    sms_services.brand_name,
                    sms_services.welcome_text,
                    tickets.enduser_short_code AS short_code,
                    tickets.marketing_04 AS marketing_no,
                    sms_services.id AS sms_service_id,
                    tickets.status,
                    tickets.operator_id,
                    tickets.recorded_message AS message,
                    tickets.subcompany,
                    tickets.summary,
                    users.name AS username,
                    users.usertype AS operator_type,
                    caller_info.enduser_marketing_number AS enduser_marketing_no,
                    tickets.notes,
                    tickets.stop,
                    tickets.reason_for_contact,
                    tickets.category
                FROM
                    sms_services
                INNER JOIN company_info ON company_info.id = sms_services.company_info_id
                INNER JOIN tickets ON tickets.service_1300 = sms_services.service_1300
                INNER JOIN caller_info ON tickets.caller_info_mobile = caller_info.enduser_mobile
                INNER JOIN users ON users.id = tickets.operator_id
                WHERE
                    tickets.ticket_id = :ticket_id";

        $rawResult = DB::connection('helpdesk')->selectOne($sql, ['ticket_id' => $ticketId]);
        $ticketArray = $rawResult ? (array) $rawResult : [];

        return TicketDetailData::from($ticketArray);
    }
}
