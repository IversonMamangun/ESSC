<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Casts;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Table('users')]
#[Fillable(['name', 'email','phone' ,'password', 'user_type_id'])]
#[Hidden(['password', 'remember_token'])]
#[Casts(['email_verified_at' => 'datetime', 'password' => 'hashed'])]
class User extends Authenticatable
{
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}