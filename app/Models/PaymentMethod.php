<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'gateway_type',
])]
class PaymentMethod extends Model
{
    use HasFactory;

    // protected function casts(): array
    // {
    //     return [
    //         //
    //     ];
    // }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
