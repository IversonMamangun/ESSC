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
    'store_id',
    'checkout_id',
    'order_number',
    'status',
    'subtotal',
    'shipping_fee',
    'discount',
    'total',
    'notes',
    'recipient_name',
    'recipient_phone',
    'region',
    'province',
    'city',
    'barangay',
    'street',
    'unit_bldg_house',
    'postal_code',
    'landmark',
    'confirmed_at',
    'processing_at',
    'packed_at',
    'shipped_at',
    'delivered_at',
    'completed_at',
    'cancelled_at',
    'return_requested_at',
    'return_approved_at',
    'returned_at',
])]
class Order extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'store_id' => 'integer',
            'checkout_id' => 'integer',
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'processing_at' => 'datetime',
            'packed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'return_requested_at' => 'datetime',
            'return_approved_at' => 'datetime',
            'returned_at' => 'datetime',
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

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class);
    }

    // cod helper method
    public function isCod(): bool
    {
        return $this->checkout?->payment?->payment_method_id === PaymentMethod::CASH_ON_DELIVERY;
    }

    // online helper method
    public function isOnline(): bool
    {
        return $this->checkout?->payment?->payment_method_id === PaymentMethod::PAY_ONLINE;
    }

    // need withExists() when accessing $this->has_unreviewed_items
    public function isEligibleForRating(): bool
    {
        return $this->status === OrderStatus::COMPLETED
            && $this->completed_at?->greaterThanOrEqualTo(now()->subMonths(3))
            && $this->has_unreviewed_items;
    }

    // need withExists() when accessing $this->has_reviewed_items
    public function isEligibleForEditingRating(): bool
    {
        return $this->status === OrderStatus::COMPLETED
            && $this->completed_at?->greaterThanOrEqualTo(now()->subMonths(3))
            && $this->has_reviewed_items;
    }

    // need withExists() when accessing $this->has_returnable_items
    public function isEligibleForReturn(): bool
    {
        return $this->status === OrderStatus::DELIVERED
            && $this->delivered_at?->greaterThanOrEqualTo(now()->subDays(7))
            && $this->has_returnable_items;
    }
}