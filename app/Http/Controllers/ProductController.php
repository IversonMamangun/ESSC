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
        'price' => $product->price, // Don't number_format here, Vue needs the raw number for the logic
        'stock' => $product->stock,
        'description' => $product->description,
        'store' => $product->store, // Pass the whole store object so props.product.store works
        'category' => $product->category->name ?? 'Uncategorized',
        'image' => $product->image, // Send the RAW path (e.g., "products/xyz.jpg" or "assets/products/xyz.jpg")
        'video' => $product->video,
        'is_top_deal' => $product->is_top_deal,
        'rating' => 5.0,
        'sold_count' => 0,
    ];

    return Inertia::render('store/Show', [
        'product' => $formattedProduct
    ]);
}
}