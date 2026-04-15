<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;
    // The columns we are allowed to mass-assign
    protected $fillable = [
        'name', 'location', 'logo', 'banner', 'rating', 'followers'
    ];

    // A Store has many Products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}