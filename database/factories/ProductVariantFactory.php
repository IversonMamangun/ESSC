<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $price = fake()->numberBetween(500, 5000);

        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(Str::random(10)),
            'is_default' => false,
            'price' => $price,
            'compare_price' => $price + fake()->numberBetween(100, 1000),
            'stock' => fake()->numberBetween(0, 100),
            'image' => fake()->imageUrl(640, 640, 'tools'),
            'weight' => fake()->randomFloat(2, 0.5, 25),
        ];
    }
}