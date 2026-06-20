<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\MoviderSmsService;

class SendOtpSmsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $phone, public string $message) 
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(MoviderSmsService $sms): void 
    {
        $sms->send(
            $this->phone,
            "Your verification code is {$this->message}"
        );
    }
}
