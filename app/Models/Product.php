<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table('products')]
#[Fillable(['store_id', 'category_id', 'title', 'price', 'discount_price', 'is_top_deal', 'stock', 'image', 'description'])]
#[Casts(['price' => 'decimal:2', 'discount_price' => 'decimal:2', 'is_top_deal' => 'boolean', 'stock' => 'integer'])]
class Product extends Model
{
    use HasFactory;

    // Added 'discount_percentage' so Vue always gets the % off
    protected $appends = ['sold_count', 'discount_percentage'];

    public function getSoldCountAttribute(): int
    {
        return (int) $this->orders()->sum('order_product.quantity');
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->discount_price || $this->price <= 0 || $this->discount_price >= $this->price) {
            return 0;
        }
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot(['quantity', 'price_at_time'])
            ->withTimestamps();
    }
}