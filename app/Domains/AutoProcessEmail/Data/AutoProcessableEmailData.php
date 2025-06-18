<?php

namespace App\Features\AutoProcessEmail\Data;

use Spatie\LaravelData\Data;

class AutoProcessableEmailData extends Data
{
    public function __construct(
        public int $id,
        public int $query_email_id,
        public ?string $sender_name,
        public ?string $sender_email,
        public ?string $original_sender_email,
        public ?string $receiver_email,
        public ?string $cc,
        public ?string $email_subject,
        public ?string $email_content,
        public ?string $email_attachment,
        public string $email_received_date,
        public ?string $status,
        public ?string $special_emails_rule_status,
        public ?string $report,
        public ?string $generated_ticket_id,
        public ?string $type,
        public ?int $email_queries_reply_templates_id,
        public ?string $created_date,
        public ?string $updated_date,
        public ?int $no_of_auto_processed,
        public ?int $sms_services_id,
        public ?string $brand_name,
        public ?string $query_email,
        public ?string $query_email_password,
        public ?string $country_iso_alpha2,
        public ?string $job_class,
        public ?array $detect_languages,
        public ?array $forward_to
    ) {}
}
