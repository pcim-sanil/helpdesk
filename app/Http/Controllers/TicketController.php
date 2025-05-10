<?php

namespace App\Http\Controllers;

use App\Features\AutoProcessEmail\AuroProcessEmailService;
use App\Features\AutoProcessEmail\Data\AutoProcessableEmailData;
use App\Features\AutoProcessEmail\Jobs\AutoProcessEmailJob;

class TicketController extends Controller
{

    public function index(AuroProcessEmailService $autoProcessEmailService)
    {
        $emails = $autoProcessEmailService->getEmailsToBeProcessed([285], 10);

        $emails->each(function (AutoProcessableEmailData $email) {

            AutoProcessEmailJob::dispatch($email);
            //$this->processEmail($email);
        });
    }


    /*

    private function processEmail(AutoProcessableEmailData $email)
    {
        if($email->no_of_auto_processed > 0) {
              $this->logAutoProcessedEmail($email, ['response_type' => AutoProcessedEmail::RESPONSE_TYPE_FORWARD]);
            return;
        }

        try {
            $potentialMobileNumbers = (new EmailQueriesComponent())->scanMobileNumberFromEmail($email['id'], true);

            if (empty($potentialMobileNumbers) || !is_array($potentialMobileNumbers)) {
                $this->mobileNumberNotFound($email);
                continue;
            }

            $this->checkSubscriptions($email, $potentialMobileNumbers);
        } catch (Throwable $throwable) {
            print_r([$throwable->getMessage(), $throwable->getTraceAsString(), __FILE__ . '@' . __LINE__]);
            Utility::l([$throwable->getMessage(), __LINE__], false, __FILE__);
        }

        dump($email->sender_email);
    }

    private function logAutoProcessedEmail(array $email, array $data = []): void
    {
        try {
            $autoProcessedEmail = new AutoProcessedEmail();

            $autoProcessedEmail->query_email_id = $email['id'];
            $autoProcessedEmail->sms_services_id = $email['sms_services_id'];
            $autoProcessedEmail->sender_email = $email['sender_email'];
            $autoProcessedEmail->processed_on = date('Y-m-d H:i:s');
            $autoProcessedEmail->response_type = $data['response_type'] ?? null;
            $autoProcessedEmail->response_template_path = $data['response_template_path'] ?? null;
            $autoProcessedEmail->has_active_subscription = $data['has_active_subscription'] ?? null;
            $autoProcessedEmail->was_unsubscribed = $data['was_unsubscribed'] ?? null;
            $autoProcessedEmail->subscriptions_response = $data['subscriptions_response'] ?? null;
            $autoProcessedEmail->unsubscribe_response = $data['unsubscribe_response'] ?? null;
            $autoProcessedEmail->reply_query_email_id = $data['reply_query_email_id'] ?? null;
            $autoProcessedEmail->forwarded = $data['forwarded'] ?? null;
            $autoProcessedEmail->mobile_numbers = $data['mobile_numbers'] ?? null;
            $autoProcessedEmail->forward_to = json_encode(self::FORWARD_TO);

            if ($autoProcessedEmail->save(false)) {
                print_r($autoProcessedEmail);
                AutoProcessedEmail::processEmail($autoProcessedEmail);
            }
        } catch (Throwable $throwable) {
            print_r([$throwable->getMessage(), $throwable->getTraceAsString(), __FILE__ . '@' . __LINE__]);
            Utility::l([$throwable->getMessage(), __LINE__], false, __FILE__);
        }
    }
        */
}
