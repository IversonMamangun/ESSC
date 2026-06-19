<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Power Tools',
            'Hand Tools',
            'Safety Equipment',
            'Heavy Machinery',
            'Electrical Supplies',
            'Hardware & Fasteners',
            'Home Appliances',
            'Cooling & Air Heating or Kitchen Appliances',
            'Water Dispensers, Purifiers & Filters',
            'Home & Living',
            'Houseware or Home Care',
            'Pest Control',
            'Cleaning Agent / Disinfectant',
            'Houseware / Home Care',
            'All Purpose Cleaners or Dishwashing / Kitchen Cleaners',
            'Home Care or Automotive Care',
            'Polishes, Waxes & Conditioners or Car Polish & Waxes',
            'Motors',
            'Automotive Care',
            'Polish, Waxes & Sealants / Car Polishes & Waxes',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
    