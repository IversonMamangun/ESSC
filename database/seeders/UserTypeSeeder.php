<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['id' => UserType::ADMIN, 'name' => 'admin'],
            ['id' => UserType::SELLER, 'name' => 'seller'],
            ['id' => UserType::CUSTOMER, 'name' => 'customer'],
        ];

        foreach ($types as $type) {
            UserType::updateOrCreate(
                ['id' => $type['id']],
                [
                    'name' => $type['name'],
                    'slug' => Str::slug($type['name']),
                ]
            );
        }
    }
}