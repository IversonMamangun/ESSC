<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'location' => fake()->city(),
            'logo' => null, // We will leave images null for now
            'banner' => null,
            'rating' => fake()->randomFloat(1, 3.5, 5.0), // Random rating between 3.5 and 5.0
            'followers' => fake()->numberBetween(100, 10000),
        ];
    }
}