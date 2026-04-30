<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    /**
     * Show the Seller Dashboard
     */
    public function index(): Response
    {
        $store = Auth::user()->store;
        
        $products = [];
        if ($store) {
            $products = Product::where('store_id', $store->id)
                ->with('category') 
                ->latest()
                ->get();
        }

        return Inertia::render('seller/Dashboard', [
            'store' => $store,
            'products' => $products,
        ]);
    }

    /**
     * Handle the "Open My Store" form.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:stores,name',
            'description' => 'required|string',
        ]);

        Store::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name), 
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Congratulations! Your store is now open.');
    }

    /**
     * Show the form to create a new product.
     */
    public function createProduct(): Response|RedirectResponse
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('seller.dashboard')->with('error', 'You must set up your store before adding products.');
        }

        $categories = Category::all();

        return Inertia::render('seller/products/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store the newly created product in the database.
     */
    public function storeProduct(Request $request): RedirectResponse
    {
        // 1. Validation Updated: Expecting an array named 'images'
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'required|array|min:1', // Must have at least 1 image
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Validate each image in the array
        ]);

        $store = Auth::user()->store;

        // 2. Handle Multiple Image Uploads
        $mainImagePath = null;
        
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            
            // For now, we take the FIRST image as the main product image
            // to fit your current database schema.
            $mainImagePath = $files[0]->store('products', 'public');
            
            /* Note: If you create a 'product_images' table later, 
               you can loop through all $files and save them there.
            */
        }

        // 3. Save the product
        Product::create([
            'store_id' => $store->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $mainImagePath, // Saving the first image path
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Product published successfully!');
    }
}