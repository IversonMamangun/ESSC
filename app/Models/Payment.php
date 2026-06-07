<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id',
    'payment_method_id',
    'is_paid',
    'payment_date',
    'amount',
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
            'order_id' => 'integer',
            'payment_method_id' => 'integer',
            'is_paid' => 'boolean',
            'payment_date' => 'date',
            'amount' => 'integer',
            'gateway_response' => 'array',
            'meta' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
