<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Sending OTPs: max 3 per phone every 10 minutes, max 15 per IP per hour
        RateLimiter::for('otp-send', function (Request $request) {
            return [
                Limit::perMinutes(10, 3)->by('otp-send:phone:'.$request->input('phone')),
                Limit::perHour(15)->by('otp-send:ip:'.$request->ip()),
            ];
        });

        // Verifying: max 10 attempts per phone every 10 minutes (brute-force guard on the 6-digit code)
        RateLimiter::for('otp-verify', function (Request $request) {
            return Limit::perMinutes(10, 10)->by('otp-verify:phone:'.$request->input('phone'));
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
