<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'name', 
    'slug'
])]
class UserType extends Model
{
    public const ADMIN = 1;
    public const SELLER = 2;
    public const CUSTOMER = 3;

    // protected $fillable = [];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}