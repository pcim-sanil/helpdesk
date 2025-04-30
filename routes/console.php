<?php

use App\Jobs\TestLogJob;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\Helpdesk\AutoProcessEmailCommand;

Schedule::command(AutoProcessEmailCommand::class)
        ->everyMinute()
        ->withoutOverlapping()
        ->onOneServer();

Schedule::job(new TestLogJob)
        ->everyMinute()
        ->withoutOverlapping()
        ->onOneServer();