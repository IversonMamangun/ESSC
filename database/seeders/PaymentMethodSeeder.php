<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Cash on Delivery',
                'slug' => 'cash-on-delivery',
                'gateway_type' => null,
            ],
            [
                'name' => 'PayMaya',
                'slug' => 'paymaya',
                'gateway_type' => null,
            ],
            [
                'name' => 'QR Ph',
                'slug' => 'qr-ph',
                'gateway_type' => null,
            ],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['slug' => $method['slug']],
                [
                    'name' => $method['name'],
                    'gateway_type' => $method['gateway_type'],
                ]
            );
        }

    }
}