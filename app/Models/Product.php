<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'store_id', 'title', 'description', 'price', 
        'old_price', 'image', 'stock', 'sold', 'rating'
    ];

    // A Product belongs to a specific Store
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}