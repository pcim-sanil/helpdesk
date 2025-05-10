<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\AutoProcessEmailCommand;

Schedule::command(AutoProcessEmailCommand::class)
        ->everyMinute()
        ->withoutOverlapping()
        ->onOneServer();
