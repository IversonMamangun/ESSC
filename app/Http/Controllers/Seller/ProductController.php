<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\AttributeResource;
use App\Http\Requests\Seller\ProductCreateRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user()->loadMissing(['store']);
        if (!$user->store) {
            abort(403, 'You do not have a store.');
        }

        return Inertia::render('seller/product/Create', [
            'categories' => CategoryResource::collection(
                Category::query()
                    ->whereNull('parent_id')
                    ->with('children')
                    ->get()
            )->resolve(),

            'attributes' => AttributeResource::collection(
                Attribute::with('values')->get()
            )->resolve(),
        ]);
    }

    public function store(ProductCreateRequest $request)
    {
        $user = $request->user()->loadMissing('store');
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $user, $request) {
            // product
            $product = Product::create([
                'store_id' => $user->store->id,
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug(
                    $validated['name']
                ),
                'description' => $validated['description'],
                'is_active' => $validated['is_active'],
                'is_featured' => $validated['is_featured'],
            ]);

            // categories
            $product->categories()->sync(
                $validated['category_ids']
            );

            // product images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store(
                        'products/images',
                        'public'
                    );

                    $product->images()->create([
                        'image' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }

            // video
            if ($request->hasFile('video')) {
                $videoPath = $request
                    ->file('video')
                    ->store(
                        'products/videos',
                        'public'
                    );

                $product->update([
                    'video' => $videoPath,
                ]);
            }

            // variants
            foreach ($validated['variants'] as $variantData) {
                $variantImagePath = null;

                if (
                    isset($variantData['image'])
                    && $variantData['image']
                ) {
                    $variantImagePath =
                        $variantData['image']->store(
                            'products/variants',
                            'public'
                        );
                }

                $variant = $product
                    ->variants()
                    ->create([
                        'sku' => $variantData['sku'],
                        'is_default' => $variantData['is_default'],
                        'price' => $variantData['price'],
                        'compare_price' => $variantData['compare_price'],
                        'stock' => $variantData['stock'],
                        'weight' => $variantData['weight'],
                        'image' => $variantImagePath,
                    ]);

                // attributes
                $attributeValueIds = [];
                foreach ($variantData['attributes'] as $attributeData) {
                    $attribute = Attribute::findOrFail(
                        $attributeData['attribute_id']
                    );

                    // existing value
                    if (!empty($attributeData['value_id'])) {
                        $attributeValue =
                            AttributeValue::query()
                                ->where(
                                    'id',
                                    $attributeData['value_id']
                                )
                                ->where(
                                    'attribute_id',
                                    $attribute->id
                                )
                                ->first();

                        if (!$attributeValue) {
                            throw ValidationException::withMessages([
                                'variants' => [
                                    'Invalid attribute value selected.',
                                ],
                            ]);
                        }
                    }

                    // new value
                    else {
                        $attributeValue =
                            AttributeValue::firstOrCreate(
                                [
                                    'attribute_id' => $attribute->id,
                                    'value' => trim(
                                        $attributeData['value']
                                    ),
                                ]
                            );
                    }

                    $attributeValueIds[] = $attributeValue->id;
                }

                $variant
                    ->attributeValues()
                    ->sync($attributeValueIds);
            }
        });

        return redirect()->route('seller.dashboard')
        ->with('success', 'Product published successfully!');
    }

    protected function generateUniqueSlug(string $name): string 
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug =
                $originalSlug
                .'-'
                .$counter;
            $counter++;
        }

        return $slug;
    }
}
