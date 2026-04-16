<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Table('user_types')]
#[Fillable(['name', 'slug'])]
class UserType extends Model
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}