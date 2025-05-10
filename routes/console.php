<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\Helpdesk\AutoProcessEmailCommand;

Schedule::command(AutoProcessEmailCommand::class)
        ->everyMinute()
        ->withoutOverlapping()
        ->onOneServer();
