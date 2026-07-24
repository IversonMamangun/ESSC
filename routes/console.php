<?php

use Illuminate\Support\Facades\Schedule;

// cron job for queue -- production otp sms
Schedule::command('queue:work --stop-when-empty --max-time=50')
    ->everyMinute()
    ->withoutOverlapping();

// cron job for auto completion of delivered orders
Schedule::command('orders:complete-delivered')
    ->dailyAt('00:30')
    ->withoutOverlapping()
    ->onOneServer();