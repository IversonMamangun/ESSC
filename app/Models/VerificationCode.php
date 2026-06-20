<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'type',
    'purpose',
    'target',
    'otp',
    'verification_token',
    'expires_at',
    'verified_at',
    'attempts',
])]
class VerificationCode extends Model
{
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function isVerified(): bool
    {
        return ! is_null($this->verified_at);
    }
}
