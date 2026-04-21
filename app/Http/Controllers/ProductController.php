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
        $product = Product::with(['store', 'category'])->findOrFail($id);
        
        $formattedProduct = [
            'id' => $product->id,
            'title' => $product->title,
            'price' => number_format($product->price, 2),
            'oldPrice' => null, 
            'stock' => $product->stock,
            'description' => $product->description,
            'storeName' => $product->store->name ?? 'Unknown Store',
            'storeId' => $product->store_id ?? null, 
            'category' => $product->category->name ?? 'Uncategorized',
            'location' => 'Philippines', 
            'images' => $product->image ? ['/storage/' . $product->image] : ['/assets/store/online-store.jpg'],
            'rating' => 5.0,
            'reviews' => 0,
            'sold' => 0,
        ];

        return Inertia::render('store/Show', [
            'product' => $formattedProduct
        ]);
    }
}