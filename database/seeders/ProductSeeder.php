<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Find the production ESSC store dynamically
        $store = Store::where('slug', 'everlasting-star-sales-corporation')->first();

        if (!$store) {
            $this->command->error('ESSC Store not found! Please run DatabaseSeeder first.');
            return;
        }

        // 2. Define the production dataset
        $productsData = [
            [
                'name' => 'A10 Atmospheric Water Generator',
                'slug' => 'a10-atmospheric-water-generator-mrenzrfk',
                'is_active' => true,
                'is_featured' => true,
                'description' => "A10 Atmospheric Water Generator\r\n\r\n💧 Turn Air Into Pure Drinking Water\r\n\r\n✔ Produces Up to 10 Liters Daily\r\n✔ 6-Stage Advanced Filtration\r\n✔ Kills 99.9% of Microorganisms\r\n✔ Energy Efficient & Eco-Friendly\r\n\r\nPURE WATER • CLEAN AIR • SMART LIVING\r\n\r\nCapacity: 10L / Day\r\nPower: 230W\r\nWater Type: Cold Water\r\n\r\nPRODUCT DESCRIPTION\r\n\r\nA10 Atmospheric Water Generator is an advanced system that converts air into clean and safe drinking water. Inspired by natural micro-structures, it uses innovative technology to extract moisture from the air and purify it through a multi-stage filtration process.",
                'video' => null,
                'views' => 0,
                'categories' => [
                    'Home Appliances',
                    'Cooling & Air Heating / Kitchen Appliances',
                    'Water Dispensers, Purifiers & Filters'
                ],
                'images_count' => 2,
                'variants' => [
                    [
                        'sku' => 'AWG-A10-10L',
                        'is_default' => true,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 10.00,
                        'attributes' => [
                            'Capacity' => '10 Liters'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'CATFISH',
                'slug' => 'catfish-ob5d32i4',
                'is_active' => true,
                'is_featured' => true,
                'description' => "🦟 Mosquito Killer & Disinfectant Tablet\r\n\r\n🔥 Powerful Smoke Action\r\n✔ Kills Up to 98% of Mosquitoes\r\n✔ Fast-Acting Formula\r\n✔ With Natural Plant Fragrance\r\n✔ Safe for Home & Indoor Use\r\n\r\n💨 Clean Air • Safe Space • Effective Protection\r\n\r\n100 Tablets\r\nFor Indoor Use Only\r\n\r\nPRODUCT DESCRIPTION\r\n\r\nCATFISH Mosquito Killer & Disinfectant Tablet is a new-generation anti-mosquito solution made from Artemisia Argyi leaves and Tetramethrin. It produces a powerful smoke that effectively eliminates mosquitoes while helping purify and disinfect indoor air.\r\n\r\nSafe for use in homes, hotels, and enclosed spaces when used as directed.",
                'video' => null,
                'views' => 0,
                'categories' => [
                    'Home & Living',
                    'Houseware / Home Care',
                    'Pest Control'
                ],
                'images_count' => 2,
                'variants' => [
                    [
                        'sku' => 'CAT-MKD-100TAB',
                        'is_default' => true,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 0.35,
                        'attributes' => [
                            'Quantity' => '100 Tablets'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'IQUISAN 202',
                'slug' => 'iquisan-202-9qnj6eut',
                'is_active' => true,
                'is_featured' => true,
                'description' => "Sanitizer & Disinfectant\r\n\r\nKills Germs • Eliminates Odor • Safe for Multiple Surfaces\r\n\r\n✔ Effective Against Bacteria & Microorganisms\r\n✔ Ideal for Homes, Offices, Hospitals & Food Areas\r\n✔ Fast-Acting & Reliable Protection\r\n\r\nNet Content: 1L / 4L/  20L\r\nForm: Liquid\r\nColor: Pale Yellow\r\n\r\nPRODUCT DESCRIPTION\r\n\r\nLIQUISAN 202 is a high-performance sanitizer and disinfectant formulated with advanced quaternary ammonium compounds. It is designed to effectively eliminate harmful bacteria while maintaining surface cleanliness and stability even in hard water conditions.",
                'video' => null,
                'views' => 0,
                'categories' => [
                    'Home & Living',
                    'Houseware / Home Care',
                    'Cleaning Agent / Disinfectant'
                ],
                'images_count' => 2,
                'variants' => [
                    [
                        'sku' => 'LIQ202-SAN-DIS-1L',
                        'is_default' => true,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 1.00,
                        'attributes' => ['Capacity' => '1 Liter']
                    ],
                    [
                        'sku' => 'LIQ202-SAN-DIS-4L',
                        'is_default' => false,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 4.00,
                        'attributes' => ['Capacity' => '4 Liters']
                    ],
                    [
                        'sku' => 'LIQ202-SAN-DIS-20L',
                        'is_default' => false,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 20.00,
                        'attributes' => ['Capacity' => '20 Liters']
                    ],
                ]
            ],
            [
                'name' => 'MEGAZYME',
                'slug' => 'megazyme-oezxkirn',
                'is_active' => true,
                'is_featured' => true,
                'description' => "Multi-Purpose Liquid Cleaner\r\n\r\n🍋 Powerful • Concentrated • Fast-Acting\r\n\r\n✔ Lifts Dirt & Grease Easily\r\n✔ Strong Emulsifying Action\r\n✔ With Natural Citrus Extract\r\n✔ Safe & Economical Formula\r\n\r\nIdeal For:\r\nIndustrial • Household • Kitchen • Engine Degreasing\r\n\r\nNet Content: 1L / 4L/  20L\r\nForm: Liquid\r\n\r\nPRODUCT DESCRIPTION\r\n\r\nMEGAZYME is a high-performance multi-purpose liquid detergent cleaner formulated with a balanced blend of non-ionic and ionic synthetic detergents. Enhanced with natural lemon citrus extract, it delivers strong cleaning power for removing grease, dirt, and grime across various applications.",
                'video' => null,
                'views' => 0,
                'categories' => [
                    'Home & Living',
                    'Houseware / Home Care',
                    'All Purpose Cleaners / Dishwashing & Kitchen Cleaners'
                ],
                'images_count' => 2,
                'variants' => [
                    [
                        'sku' => 'MZM-MPC-LIQ-1L',
                        'is_default' => true,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 1.00,
                        'attributes' => ['Capacity' => '1 Liter']
                    ],
                    [
                        'sku' => 'MZM-MPC-LIQ-4L',
                        'is_default' => false,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 4.00,
                        'attributes' => ['Capacity' => '4 Liters']
                    ],
                    [
                        'sku' => 'MZM-MPC-LIQ-20L',
                        'is_default' => false,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 20.00,
                        'attributes' => ['Capacity' => '20 Liters']
                    ],
                ]
            ],
            [
                'name' => 'RAREZEL 904',
                'slug' => 'rarezel-904-int2wnen',
                'is_active' => true,
                'is_featured' => true,
                'description' => "Cleaning Compound Solvent & Rust Preventive\r\n(Military Application)\r\n\r\n✔ Non-Staining Formula\r\n✔ Water Displacing\r\n✔ Rust Protection up to 12 Months\r\n✔ Multi-Purpose Metal Protection\r\n\r\n5-IN-1 ACTION:\r\nPenetrant • Rust Remover • Moisture Displacer • Rust Preventive • Gun Bore Cleaner\r\n\r\nPRODUCT DESCRIPTION\r\n\r\nRAREZEL 904 is a non-staining, non-emulsifiable, water-displacing rust preventive compound designed for military-grade applications. It forms a thin protective film that prevents corrosion without leaving messy residues or bleeding through packaging.\r\n\r\nIdeal for indoor storage and transit protection, it safeguards metal surfaces for up to 12 months, even under harsh conditions.\r\n\r\nIt effectively dissolves rust, removes moisture, and protects sensitive components without damaging metal surfaces. Suitable for firearms, artillery, automotive parts, and industrial metal components.",
                'video' => null,
                'views' => 0,
                'categories' => [
                    'Motors',
                    'Automotive Care',
                    'Polishes, Waxes & Sealants / Car Polishes & Waxes'
                ],
                'images_count' => 2,
                'variants' => [
                    [
                        'sku' => 'RZ904-CCS-RST-1L',
                        'is_default' => true,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 1.00,
                        'attributes' => ['Capacity' => '1 Liter']
                    ],
                    [
                        'sku' => 'RZ904-CCS-RST-4L',
                        'is_default' => false,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 4.00,
                        'attributes' => ['Capacity' => '4 Liters']
                    ],
                    [
                        'sku' => 'RZ904-CCS-RST-20L',
                        'is_default' => false,
                        'price' => 888888.00,
                        'compare_price' => 999999.00,
                        'stock' => 99,
                        'weight' => 20.00,
                        'attributes' => ['Capacity' => '20 Liters']
                    ],
                ]
            ],
        ];

        foreach ($productsData as $data) {
            // A. Create or update the core product
            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'store_id' => $store->id,
                    'name' => $data['name'],
                    'is_active' => $data['is_active'],
                    'is_featured' => $data['is_featured'],
                    'description' => $data['description'],
                    'video' => $data['video'],
                    'views' => $data['views'],
                ]
            );

            // B. Sync Categories (Many-to-Many link)
            $categoryIds = Category::whereIn('name', $data['categories'])->pluck('id')->toArray();
            $product->categories()->sync($categoryIds);

            // C. Create/Sync clean Product Gallery Images
            for ($i = 0; $i < $data['images_count']; $i++) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'sort_order' => $i
                    ],
                    [
                        'image' => "products/images/{$product->slug}-{$i}.jpg"
                    ]
                );
            }

            // D. Process product variants and attribute maps
            foreach ($data['variants'] as $vData) {
                $variant = ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'sku' => $vData['sku']
                    ],
                    [
                        'is_default' => $vData['is_default'],
                        'price' => $vData['price'],
                        'compare_price' => $vData['compare_price'],
                        'stock' => $vData['stock'],
                        'weight' => $vData['weight'],
                        'image' => "products/variants/{$vData['sku']}.jpg",
                    ]
                );

                // Process variant attribute configuration links
                $attributeValueIds = [];
                foreach ($vData['attributes'] as $attrName => $valString) {
                    // parent attribute row
                    $attribute = Attribute::firstOrCreate(['name' => $attrName]);

                    // specific value assignment linked to attribute
                    $attributeValue = AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $valString
                    ]);

                    $attributeValueIds[] = $attributeValue->id;
                }

                // Sync the variant to its structural context specifications on the pivot model
                $variant->attributeValues()->sync($attributeValueIds);
            }
        }
    }
}