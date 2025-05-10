<?php

namespace App\Console\Commands\Helpdesk;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Features\AutoProcessEmail\AuroProcessEmailService;
use Throwable;

class AutoProcessEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helpdesk:auto-process-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto process email tickets.';

    /**
     * Execute the console command.
     */
    public function handle(AuroProcessEmailService $autoProcessEmailService)
    {
        $autoProcessableEmails = $autoProcessEmailService->getEmailsToBeProcessed();

        foreach ($autoProcessableEmails as $autoProcessableEmail) {
            try {
                Log::info(sprintf('AutoProcessEmailCommand: `Processing email %s', $autoProcessableEmail->id));

                $jobClass = $autoProcessableEmail->job_class;
                $jobClass::dispatch($autoProcessableEmail);
            } catch (Throwable $e) {
                Log::error(sprintf('AutoProcessEmailCommand: `Error processing email %s', $autoProcessableEmail->id));
                Log::error($e->getMessage());
            }
        }

        return 0;
    }
}