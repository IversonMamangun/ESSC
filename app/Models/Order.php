<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use App\Enums\OrderStatus;

#[Fillable([
    //
])]
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        //
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }
    
}