<?php

use App\Jobs\TestLogJob;
use Illuminate\Support\Facades\Schedule;

Schedule::command('helpdesk:auto-process-email')
        ->everyFiveMinutes()
        ->withoutOverlapping()
        ->onOneServer();

Schedule::job(new TestLogJob)
        ->everyMinute()
        ->withoutOverlapping()
        ->onOneServer();