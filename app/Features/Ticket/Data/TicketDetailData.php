<?php

namespace App\Features\Ticket\Data;

use Spatie\LaravelData\Data;

class TicketDetailData extends Data
{
    public function __construct(
        public ?string $caller_mobile,
        public ?string $caller_email,
        public ?string $callback_number,
        public ?string $created_date,
        public ?string $caller_name,
        public ?string $service_1300,
        public ?string $enquiry_type,
        public ?string $reassigned_from,
        public ?int $company_id,
        public ?string $company_name,
        public ?string $brand_name,
        public ?string $welcome_text,
        public ?string $short_code,
        public ?string $marketing_no,
        public ?int $sms_service_id,
        public ?string $status,
        public ?int $operator_id,
        public ?string $message,
        public ?string $subcompany,
        public ?string $summary,
        public ?string $username,
        public ?string $operator_type,
        public ?string $enduser_marketing_no,
        public ?string $notes,
        public ?string $stop,
        public ?string $reason_for_contact,
        public ?string $category,
    ) {}
}
