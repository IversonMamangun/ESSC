<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(), 
            
            'category_id' => Category::inRandomOrder()->value('id') ?? 1, 
            
            'title' => fake()->words(4, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 50, 5000),
            'stock' => fake()->numberBetween(10, 100),
            'image' => null, // Images can be handled manually later
        ];
    }
}