<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        // Generate a base price so we can make the old_price slightly higher
        $price = fake()->randomFloat(2, 500, 10000); 

        return [
            'title' => ucwords(fake()->words(4, true)), // e.g., "Heavy Duty Steel Hammer"
            'description' => fake()->paragraph(),
            'price' => $price,
            'old_price' => $price + fake()->numberBetween(100, 1000),
            'image' => null, 
            'stock' => fake()->numberBetween(10, 200),
            'sold' => fake()->numberBetween(0, 500),
            'rating' => fake()->randomFloat(1, 3.8, 5.0),
        ];
    }
}