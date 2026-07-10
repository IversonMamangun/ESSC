<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Enums\OrderReturnReason;

#[Fillable([
    'order_id',
    'order_item_id',
    'reason',
    'description',
    'video',
    'rejection_reason',
])]
class OrderReturn extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'order_id' => 'integer',
            'order_item_id' => 'integer',
            'reason' => OrderReturnReason::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(OrderReturnImage::class);
    }
}
