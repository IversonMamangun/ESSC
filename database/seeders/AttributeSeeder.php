<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            'Color' => [
                'Red',
                'Blue',
                'Black',
                'Yellow',
                'Green',
            ],
            'Size' => [
                'Small',
                'Medium',
                'Large',
            ],
            'Voltage' => [
                '110V',
                '220V',
            ],
            'Material' => [
                'Steel',
                'Aluminum',
                'Carbon Fiber',
            ],
            'Capacity' => [
                '1 Liter',
                '4 Liters',
                '10 Liters',
                '20 Liters',
            ],
            'Quantity' => [
                '100 Tablets',
            ],
        ];

        foreach ($attributes as $attributeName => $values) {
            $attribute = Attribute::firstOrCreate([
                'name' => $attributeName
            ]);

            foreach ($values as $value) {
                AttributeValue::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}