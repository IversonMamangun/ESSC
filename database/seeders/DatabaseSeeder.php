<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store; 
use App\Models\Product;
use App\Models\Category;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class
        ]);

        User::factory()->create([
            'user_type_id' => UserType::ADMIN,
            'name' => 'Admin Test',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'user_type_id' => UserType::CUSTOMER,
            'name' => 'Customer Test',
            'email' => 'customer@example.com',
        ]);

        // production data
        $seller = User::factory()->create([
            'user_type_id' => UserType::SELLER,
            'name' => 'Seller Test',
            'email' => 'seller@example.com',
        ]);

        if ($seller) {
            Store::factory()->create([
                'user_id' => $seller->id,
                'name' => 'Everlasting Star Sales Corporation',
                'slug' => 'everlasting-star-sales-corporation',
                'description' => Str::squish('Everlasting Star Sales Corporation (ESSC) is a Filipino-owned enterprise 
                    established on December 16, 2015. The company specializes in the manufacturing, trading, distribution, 
                    and supply of industrial chemicals, environmental technologies, and agricultural enhancement products. 
                    ESSC is also a supplier of disinfectants, sanitizers, and other cleaning products.'),
                'is_official' => true
            ]);
        }

        // production seeder for essc
        $this->call([
            ProductSeeder::class,
            PaymentMethodSeeder::class,
        ]);
    }
}