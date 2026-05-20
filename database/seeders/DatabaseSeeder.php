<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store; 
use App\Models\Product;
use App\Models\Category;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class,
            CategorySeeder::class,
        ]);

        User::factory()->create([
            'user_type_id' => UserType::ADMIN,
            'name' => 'Admin Test',
            'email' => 'test@example.com',
        ]);

        $sellers = User::factory(3)->create([
            'user_type_id' => UserType::SELLER,
        ]);

        User::factory(5)->create([
            'user_type_id' => UserType::CUSTOMER,
        ]);

        foreach ($sellers as $seller) {
            Store::factory()->create([
                'user_id' => $seller->id,
            ]);
        }

        Product::factory(20)->create();

        // // Create the Admin User
        // $admin = User::factory()->create([
        //     'name' => 'Iverson Mamangun',
        //     'email' => 'admin@essc.test',
        //     'user_type_id' => 1,
        // ]);

        // // 1. Create the Official Store
        // $officialStore = Store::create([
        //     'user_id' => $admin->id,
        //     'name' => 'Everlasting Star Store',
        //     'slug' => 'everlasting-star',
        //     'description' => 'Official industrial chemical and sanitizer distributor.',
        //     'is_active' => true,
        // ]);

        // // 2. Define Official ESSC Products
        // $esscProducts = [
        //     ['title' => 'LIQUISAN 202 Sanitizer & Disinfectant', 'image' => 'assets/products/LIQUISAN.jpg'],
        //     ['title' => 'CATFISH Mosquito Killer & Disinfectant Tablet', 'image' => 'assets/products/CATFISH.jpg'],
        //     ['title' => 'MEGAZYME Multi-Purpose Cleaner', 'image' => 'assets/products/MEGAZYME.jpg'],
        //     ['title' => 'RARESA P-2015 Non-Buffable Liquid Wax', 'image' => 'assets/products/RARESA.jpg'],
        //     ['title' => 'A10 Atmospheric Water Generator', 'image' => 'assets/products/A10-Atmospheric.jpg'],
        // ];

        // // 3. Attach Products to Everlasting Star Store
        // foreach ($esscProducts as $item) {
        //     Product::create([
        //         'store_id' => $officialStore->id, // Linked to ESSC Store
        //         'category_id' => 1,
        //         'title' => $item['title'],
        //         'description' => "Official ESSC Product: " . $item['title'],
        //         'price' => 0.00, // Coming Soon
        //         'stock' => 100,
        //         'is_top_deal' => true,
        //         'image' => $item['image'],
        //     ]);
        // }

        // // 4. Create other randomized stores for "Discover"
        // $otherStores = Store::factory(2)->create();
        // foreach ($otherStores as $store) {
        //     for ($i = 0; $i < 4; $i++) {
        //         $randomBase = Arr::random($esscProducts);
        //         Product::create([
        //             'store_id' => $store->id,
        //             'category_id' => rand(1, 2), 
        //             'title' => $randomBase['title'] . " (Alternate)",
        //             'price' => 0.00,
        //             'stock' => rand(10, 50),
        //             'is_top_deal' => false,
        //             'image' => $randomBase['image'],
        //         ]);
        //     }
        // }
    }
}