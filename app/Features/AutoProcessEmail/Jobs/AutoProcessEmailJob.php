<?php

namespace App\Features\AutoProcessEmail\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Features\AutoProcessEmail\Data\AutoProcessableEmailData;
use Throwable;
use App\Services\MobileService;
use App\Services\LanguageDectorService;
use App\Models\Helpdesk\AutoProcessedEmailModel;
use App\Features\AutoProcessEmail\Data\AutoProcessedEmailData;
use Illuminate\Support\Facades\View;
use App\Services\PHPMailerService;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Mews\Purifier\Facades\Purifier;
use App\Models\Helpdesk\QueryEmailModel;
use App\Features\AutoProcessEmail\AutoProcessResponseTypeEnum;
use App\Models\Helpdesk\TicketModel;
use App\Features\AutoProcessEmail\AutoProcessException;
use App\Features\QueryEmail\QueryEmailService;

abstract class AutoProcessEmailJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const CC_EMAIL = 'santonil2003@hotmail.com';

    public $tries = 2;
    public int $uniqueFor = 604800;
    public $timeout = 300;

    /**
     * Create a new job instance.v 
     *
     * @param AutoProcessableEmailData $autoProcessableEmailData The email data to be processed
     */
    public function __construct(protected AutoProcessableEmailData $autoProcessableEmailData) {}

    /**
     * Return a unique ID to prevent duplicate processing of the same email.
     * 
     * @return string
     */
    public function uniqueId(): string
    {
        return $this->autoProcessableEmailData->id;
    }

    /**
     * Get the allowed languages for the job.
     *
     * @return array
     */
    public function getAllowedLanguages(): array
    {
        return $this->autoProcessableEmailData->detect_languages ?? [LanguageDectorService::ENGLISH_LANGUAGE];
    }

    /**
     * Detect the language of the email.
     *
     * @param string $text
     * @return string
     */
    public function detectLanguage(string $text): string
    {
        $languageDetector = new LanguageDectorService();
        return $languageDetector->detectLanguage($text, $this->getAllowedLanguages());
    }

    /**
     * Get the sender email preview to append to the respone email with reply email to the sender.
     *
     * @return string
     */
    private function getSenderEmailPreview(): string
    {
        try {
            // 1) Parse & format date
            $sentAt = Carbon::parse($this->autoProcessableEmailData->email_received_date)
                ->setTimezone(config('app.timezone'))
                ->format('D, j M Y H:i');

            // 2) Sanitize incoming HTML, allowing only basic tags & safe attributes
            $allowed = [
                'HTML.Allowed'          => 'p,br,strong,em,b,i,ul,ol,li,div,hr,h1,h2,h3,h4,h5,h6,a[href|title]',
                'AutoFormat.AutoParagraph' => false,
                'AutoFormat.RemoveEmpty'   => true,
            ];
            $cleanContent = Purifier::clean($this->autoProcessableEmailData->email_content, $allowed);

            // 3) Render the Blade partial with safe, escaped data
            return view('mail.partials.sender_preview', [
                'sender'  => e($this->autoProcessableEmailData->sender_email),
                'sent'    => $sentAt,
                'to'      => e($this->autoProcessableEmailData->receiver_email),
                'subject' => e($this->autoProcessableEmailData->email_subject),
                'content' => new HtmlString($cleanContent),
            ])->render();
        } catch (Throwable $e) {
            Log::channel('email_processing')->error('Failed to get sender email preview', [
                'email_id' => $this->autoProcessableEmailData->id,
                'error' => $e->getMessage(),
            ]);
        }

        return '';
    }

    /**
     * Send an email.
     *
     * @param QueryEmailModel $outgoingEmailQuery
     * @return bool
     */
    private function sendEmail(QueryEmailModel $outgoingEmailQuery): bool
    {
        $config = [];
        $config['username'] = $this->autoProcessableEmailData->query_email;
        $config['password'] = $this->autoProcessableEmailData->query_email_password;

        $mailer = new PHPMailerService($config);

        $result = $mailer->send(
            to: explode(',', $outgoingEmailQuery->receiver_email),
            cc: explode(',', $outgoingEmailQuery->cc),
            subject: $outgoingEmailQuery->email_subject,
            content: $outgoingEmailQuery->email_content,
        );

        if (!$result) {
            throw new AutoProcessException('Failed to send an email', [
                'process_email_errors' => 'Failed to send an email to ' . $outgoingEmailQuery->receiver_email,
            ]);
        }

        return $result;
    }

    /**
     * Get a suitable mobile number to update in ticket
     *
     * @param AutoProcessedEmailData $autoProcessedEmailData
     * @return string|null
     */
    private function getSuitableMobileNumber(AutoProcessedEmailData $autoProcessedEmailData): ?string
    {
        $unsubscribedMobileNumbers = $autoProcessedEmailData->getUnsubscribedMobileNumbers();
        $potentialMobileNumbers = $autoProcessedEmailData->getPotentialMobileNumbers();
        $mobileNumbers = array_merge($unsubscribedMobileNumbers, $potentialMobileNumbers);

        if (count($mobileNumbers) > 0) {
            return $mobileNumbers[0] ?? null;
        }

        return null;
    }

    /**
     * Close a ticket.
     *
     * @param AutoProcessedEmailData $autoProcessedEmailData
     * @return bool
     */
    private function closeTicket(AutoProcessedEmailData $autoProcessedEmailData): bool
    {
        $ticket = TicketModel::query()->find($this->autoProcessableEmailData->generated_ticket_id);
        $ticket->status = TicketModel::TICKET_STATUS_CLOSED;
        $ticket->operator_id = 10; // assign to back office support team
        $ticket->notes = $ticket->notes . ' #' . $autoProcessedEmailData->response_type?->value ?? '';


        if (strpos($ticket->caller_info_mobile, '.') !== false) {
            // $ticket->caller_info_mobile = $this->getSuitableMobileNumber($autoProcessedEmailData);
            // $ticket->caller_info_mobile_8 = substr($ticket->caller_info_mobile, -8);
        }

        switch ($autoProcessedEmailData->response_type) {
            case AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND:
                $ticket->summary = 16; // Replied Limited Info / No Number
                break;
            case AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND:
                $ticket->summary = 36; // Already Unsubscribed -no active subscription
                break;
            case AutoProcessResponseTypeEnum::UNSUBSCRIBED:
                $ticket->summary = 27; // Unsubscribed from service
                break;
            case AutoProcessResponseTypeEnum::CASE_FORWARDED:
                $ticket->summary = 50; // Enquiry forwarded
                break;
        }

        if ($ticket->save()) {
            return true;
        }

        throw new AutoProcessException('Failed to close ticket', [
            'close_ticket_errors' => $ticket->errors(),
        ]);
    }

    /**
     * Create an outgoing query email.
     *
     * @param AutoProcessedEmailData $autoProcessedEmailData
     * @return QueryEmailModel
     */
    private function createOutgoingQueryEmail(AutoProcessedEmailData $autoProcessedEmailData): QueryEmailModel
    {
        $outgoingEmailQuery = new QueryEmailModel();
        $outgoingEmailQuery->sender_email = $this->autoProcessableEmailData->receiver_email;
        $outgoingEmailQuery->cc = self::CC_EMAIL; // you may pass comma separated emails

        if ($autoProcessedEmailData->response_type == AutoProcessResponseTypeEnum::CASE_FORWARDED) {
            $outgoingEmailQuery->receiver_email = implode(',', $this->autoProcessableEmailData->forward_to);
            $outgoingEmailQuery->email_subject = 'FYI :' . $this->autoProcessableEmailData->email_subject;

            // fetch the email chain by incoming email query ID
            $emailQueryChain = QueryEmailService::getEmailChainByIncomingEmailQueryId($this->autoProcessableEmailData->query_email_id);

            $outgoingEmailQuery->email_content = View::make('mail.en.case-forwarded', [
                'emailQueryChain' => $emailQueryChain,
            ])->render();
        } else {
            $outgoingEmailQuery->receiver_email = $this->autoProcessableEmailData->sender_email;
            $outgoingEmailQuery->email_subject = 'Re:' . $this->autoProcessableEmailData->email_subject;

            /**
             * Build the email content.
             */
            if (empty($autoProcessedEmailData->response_template_path) || !View::exists($autoProcessedEmailData->response_template_path)) {
                throw new AutoProcessException('Invalid response template path', [
                    'response_template_path_errors' => 'Invalid response template path: ' . $autoProcessedEmailData->response_template_path,
                ]);
            }

            $content = View::make($autoProcessedEmailData->response_template_path, [
                'BRAND_NAME' => $this->autoProcessableEmailData->brand_name,
                'MOBILE_NUMBER' => !empty($autoProcessedEmailData->getUnsubscribedMobileNumbers())
                    ? implode(',', $autoProcessedEmailData->getUnsubscribedMobileNumbers())
                    : 'MSISDN',
                'SENDER_EMAIL_PREVIEW' => $this->getSenderEmailPreview(),
            ])->render();
            $outgoingEmailQuery->email_content = $content;
        }

        $outgoingEmailQuery->generated_ticket_id = $this->autoProcessableEmailData->generated_ticket_id;
        $outgoingEmailQuery->type = QueryEmailModel::TYPE_EMAIL_QUERY_OUTGOING;
        $outgoingEmailQuery->status = QueryEmailModel::STATUS_EMAIL_QUERY_REPLY_EMAIL_SENT;
        $outgoingEmailQuery->created_date = date('Y-m-d H:i:s');

        if ($outgoingEmailQuery->save()) {
            return $outgoingEmailQuery;
        }

        throw new AutoProcessException('Failed to create outgoing query email', [
            'create_outgoing_query_email_errors' => $outgoingEmailQuery->errors(),
        ]);
    }

    /**
     * Log the auto processed email.
     *
     * @param AutoProcessedEmailData $autoProcessedEmailData
     * @return void
     */
    public function logAutoProcessedEmail(AutoProcessedEmailData $autoProcessedEmailData): void
    {
        try {
            $autoProcessedEmail = new AutoProcessedEmailModel();

            $autoProcessedEmail->query_email_id = $autoProcessedEmailData->query_email_id;
            $autoProcessedEmail->sms_services_id = $autoProcessedEmailData->sms_services_id;
            $autoProcessedEmail->sender_email = $autoProcessedEmailData->sender_email;
            $autoProcessedEmail->processed_on = date('Y-m-d H:i:s');
            $autoProcessedEmail->response_type = $autoProcessedEmailData->response_type?->value ?? null;
            $autoProcessedEmail->response_template_path = $autoProcessedEmailData->response_template_path;
            $autoProcessedEmail->has_active_subscription = $autoProcessedEmailData->has_active_subscription;
            $autoProcessedEmail->was_unsubscribed = $autoProcessedEmailData->was_unsubscribed;
            $autoProcessedEmail->subscriptions_response = $autoProcessedEmailData->subscriptions_response;
            $autoProcessedEmail->unsubscribe_response = $autoProcessedEmailData->unsubscribe_response;
            $autoProcessedEmail->reply_query_email_id = $autoProcessedEmailData->reply_query_email_id;
            $autoProcessedEmail->forwarded = $autoProcessedEmailData->forwarded;
            $autoProcessedEmail->mobile_numbers = $autoProcessedEmailData->mobile_numbers;
            $autoProcessedEmail->forward_to = $autoProcessedEmailData->forward_to;
            $autoProcessedEmail->process_log = $autoProcessedEmailData->process_log;

            if ($autoProcessedEmail->save()) {
                try {
                    // Create outgoing query email
                    $outgoingEmailQuery = $this->createOutgoingQueryEmail($autoProcessedEmailData);
                    $autoProcessedEmail->reply_query_email_id = $outgoingEmailQuery->id;
                    $autoProcessedEmail->update();

                    // Process the email
                    $emailSent = $this->sendEmail($outgoingEmailQuery);
                    $autoProcessedEmail->reply_email_sent = $emailSent;
                    $autoProcessedEmail->update();

                    // close the ticket if email sent successfully
                    $this->closeTicket($autoProcessedEmailData);

                    $autoProcessedEmail->process_log = $autoProcessedEmailData->getProcessLog();
                    $autoProcessedEmail->update();
                } catch (AutoProcessException $e) {
                    $autoProcessedEmailData->setProcessLog('post_auto_process_email_errors', $e->getErrors());
                    $autoProcessedEmail->update();

                    throw $e;
                } catch (Throwable $throwable) {
                    $autoProcessedEmailData->setProcessLog('post_auto_process_email_errors', $throwable->getMessage());
                    $autoProcessedEmail->update();

                    throw $throwable;
                }
            }
        } catch (Throwable $throwable) {
            Log::channel('email_processing')->error('Auto process email job failed', [
                'email_id' => $this->autoProcessableEmailData->id,
                'error' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);
        }
    }

    /**
     * Process the email.
     *
     * @param array $mobileNumbers
     * @return AutoProcessedEmailData
     */
    abstract public function process(array $mobileNumbers): AutoProcessedEmailData;

    /**
     * Normalize the mobile number.
     *
     * @param string $msisdn
     * @return string
     */
    protected function normalize(string $msisdn): string
    {
        return preg_replace('/\D+/', '', ltrim($msisdn, '+'));
    }

    /**
     * Check if the email was auto processed.
     *
     * @return bool
     */
    protected function wasAutoProcessed(): bool
    {
        $wasAutoProcessed = $this->autoProcessableEmailData->no_of_auto_processed ?? 0;
        return $wasAutoProcessed > 0;
    }

    /**
     * Get the email template.
     *
     * @param AutoProcessResponseTypeEnum $responseType
     * @param string $language
     * @return string
     */
    protected function getEmailTemplate(AutoProcessResponseTypeEnum $responseType, string $language = LanguageDectorService::ENGLISH_LANGUAGE): string
    {
        switch ($responseType) {
            case AutoProcessResponseTypeEnum::MOBILE_NUMBER_NOT_FOUND:
                return 'mail.en.mobile-number-not-found';
            case AutoProcessResponseTypeEnum::SUBSCRIPTION_NOT_FOUND:
                return 'mail.en.subscription-not-found';
            case AutoProcessResponseTypeEnum::UNSUBSCRIBED:
                return 'mail.en.unsubscribed';
            case AutoProcessResponseTypeEnum::CASE_FORWARDED:
                return 'mail.en.case-forwarded';
            default:
                return '';
        }
    }

    /**
     * Prepare the auto processed email data for forwarding.
     *
     * @param AutoProcessedEmailData $autoProcessedEmailData
     * @return AutoProcessedEmailData
     */
    protected function prepareAutoProcessedEmailDataForForwarding(AutoProcessedEmailData $autoProcessedEmailData): AutoProcessedEmailData
    {
        $autoProcessedEmailData->setResponseType(AutoProcessResponseTypeEnum::CASE_FORWARDED);
        $autoProcessedEmailData->setResponseTemplatePath($this->getEmailTemplate(AutoProcessResponseTypeEnum::CASE_FORWARDED, LanguageDectorService::ENGLISH_LANGUAGE));

        return $autoProcessedEmailData;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MobileService $mobileService): void
    {
        try {
            Log::channel('email_processing')->info('Starting auto process email job', [
                'email_id' => $this->autoProcessableEmailData->id,
            ]);

            // scan the email content for mobile numbers
            $emailContent = $this->autoProcessableEmailData->email_subject . ' ' . $this->autoProcessableEmailData->email_content;
            $mobileNumbers = $mobileService->extractMobiles($emailContent, $this->autoProcessableEmailData->country_iso_alpha2);

            $autoProcessedEmailData = $this->process($mobileNumbers);

            $this->logAutoProcessedEmail($autoProcessedEmailData);

            Log::channel('email_processing')->info('Successfully processed email', [
                'email_id' => $this->autoProcessableEmailData->id,
            ]);
        } catch (Throwable $e) {
            Log::channel('email_processing')->error('Failed to process email', [
                'email_id' => $this->autoProcessableEmailData->id,
                'sender' => $this->autoProcessableEmailData->sender_email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        Log::channel('email_processing')->error('Auto process email job failed', [
            'email_id' => $this->autoProcessableEmailData->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
