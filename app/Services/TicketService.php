<?php

namespace App\Services;

class TicketService
{
    /**
     * Get the label for the enquiry type
     *
     * @param int $enqueryType
     * @return string
     */
    public static function getEnquiryTypeLabel(int $enqueryType): string
    {
        $enquiryTypeLabels = [
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

        return $enquiryTypeLabels[$enqueryType] ?? 'Unknown';
    }
}
