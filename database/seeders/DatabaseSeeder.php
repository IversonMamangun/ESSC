<?php

namespace Database\Seeders;

use App\Models\User;
// 1. Add these two lines so Laravel knows where your models are!
use App\Models\Store; 
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Your existing User code
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. Now it knows what Store and Product are
        Store::factory(10)->create()->each(function ($store) {
            // For each store that is created, generate 20 products linked to it
            Product::factory(20)->create([
                'store_id' => $store->id
            ]);
        });
    }
}