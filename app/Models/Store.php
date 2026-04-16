<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;

#[Table('stores')]
#[Fillable(['user_id', 'name', 'slug', 'description', 'is_active'])]
#[Casts(['is_active' => 'boolean'])]
class Store extends Model
{
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}