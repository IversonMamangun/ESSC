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
#[Fillable(['store_id', 'category_id', 'title', 'price', 'stock', 'image', 'description'])]
#[Casts(['price' => 'decimal:2', 'stock' => 'integer'])]
class Product extends Model
{
    use HasFactory;

    // 1. Tell Laravel to ALWAYS include 'sold_count' when sending data to Vue
    protected $appends = ['sold_count'];

    // 2. Automatically calculate the total sold items based on the pivot table
    public function getSoldCountAttribute(): int
    {
        // This looks at all orders for this product and sums up the 'quantity' from the pivot table
        return (int) $this->orders()->sum('order_product.quantity');
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