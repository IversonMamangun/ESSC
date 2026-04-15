<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Buyer', 'slug' => 'buyer'],
            ['name' => 'Seller', 'slug' => 'seller'],
        ];

        foreach ($types as $type) {
            UserType::firstOrCreate(['slug' => $type['slug']], $type);
        }
    }
}