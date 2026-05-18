<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'is_default',
        'price',
        'compare_price',
        'stock',
        'image',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_attribute_values'
        )
        ->using(ProductVariantAttributeValue::class)
        ->withTimestamps();
    }
}