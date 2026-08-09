<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PaymentStatus;

#[Fillable([
    'checkout_id',
    'payment_method_id',
    'status',
    'payment_date',
    'amount',
    'cancelled_amount',
    'gateway',
    'gateway_payment_intent_id',
    'gateway_payment_id',
    'gateway_response',
    'meta',
])]
class Payment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'checkout_id' => 'integer',
            'payment_method_id' => 'integer',
            'status' => PaymentStatus::class,
            'payment_date' => 'date',
            'amount' => 'integer',
            'cancelled_amount' => 'integer',
            'gateway_response' => 'array',
            'meta' => 'array',
        ];
    }

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class);
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
