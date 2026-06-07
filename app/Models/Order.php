<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Enums\OrderStatus;

#[Fillable([
    'user_id',
    'order_number',
    'status',
    'subtotal',
    'shipping_fee',
    'discount',
    'total',
    'recipient_name',
    'recipient_phone',
    'region',
    'province',
    'city',
    'barangay',
    'street',
    'postal_code',
    'landmark',
])]
class Order extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}