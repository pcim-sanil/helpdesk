<?php

namespace App\Services;

class TicketService
{
    /**
     * Get the status options
     *
     * @return array
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
     *
     * @return array
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
     *
     * @param int $enqueryType
     * @return string
     */
    public static function getEnquiryTypeLabel(int $enqueryType): string
    {
        $enquiryTypeLabels = self::getEnquiryTypeOptions();

        return $enquiryTypeLabels[$enqueryType] ?? 'Unknown';
    }
}
