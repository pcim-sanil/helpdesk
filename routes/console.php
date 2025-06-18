<?php

use App\Console\Commands\Helpdesk\AutoProcessEmailCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(AutoProcessEmailCommand::class)
    ->everyFifteenMinutes()
    ->withoutOverlapping()
    ->onOneServer();
