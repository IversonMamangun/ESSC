<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display the specified product.
     */
    public function show($id): Response
    {
        // 1. Find the product in the database or fail with a 404
        $product = Product::with(['store', 'category'])->findOrFail($id);
        // 2. Format the data so it matches exactly what Show.vue expects
        $formattedProduct = [
            'id' => $product->id,
            'title' => $product->title,
            'price' => number_format($product->price, 2),
            'oldPrice' => null, 
            'stock' => $product->stock,
            'description' => $product->description,
            'storeName' => $product->store->name ?? 'Unknown Store',
            'category' => $product->category->name ?? 'Uncategorized',
            'location' => 'Philippines', 
            'images' => $product->image ? ['/storage/' . $product->image] : ['/assets/store/online-store.jpg'],
            'rating' => 5.0,
            'reviews' => 0,
            'sold' => 0,
        ];

        // 3. Send it to the Vue component
        return Inertia::render('store/Show', [
            'product' => $formattedProduct
        ]);
    }
}