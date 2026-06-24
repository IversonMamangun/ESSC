<?php

use Illuminate\Support\Facades\Schedule;

// cron job for queue -- production otp sms
Schedule::command('queue:work --stop-when-empty --max-time=50')
    ->everyMinute()
    ->withoutOverlapping();