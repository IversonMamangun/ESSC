<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        // Get an existing SELLER without a store
        $seller = User::where('user_type_id', UserType::SELLER)
            ->whereDoesntHave('store')
            ->inRandomOrder()
            ->first();

        // If none exist, create one
        if (!$seller) {
            $seller = User::factory()->create([
                'user_type_id' => UserType::SELLER,
            ]);
        }

        return [
            'user_id' => $seller->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}