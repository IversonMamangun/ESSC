<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;

#[Table('products')]
#[Fillable(['store_id', 'category_id', 'title', 'price', 'stock', 'image', 'description'])]
#[Casts(['price' => 'decimal:2', 'stock' => 'integer'])]
class Product extends Model
{
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