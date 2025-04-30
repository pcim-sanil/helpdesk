<?php

namespace App\Console\Commands\Helpdesk;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    public function handle()
    {
        Log::info('AutoProcessEmailCommand executed at '.now());
        return 0;
    }
}
