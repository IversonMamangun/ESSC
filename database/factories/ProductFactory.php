<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(4, true);

        // Get an existing store or create one
        $store = Store::inRandomOrder()->first()
            ?? Store::factory()->create();

        return [
            'store_id' => $store->id,
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
            'description' => fake()->paragraph(),
            'views' => fake()->numberBetween(0, 1000),
        ];
    }

    /**
     * Attach categories AFTER product creation
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $categoryIds = Category::inRandomOrder()
                ->limit(rand(1, 3))
                ->pluck('id');

            $product->categories()->attach($categoryIds);
        });
    }
}