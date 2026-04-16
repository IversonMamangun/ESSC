<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Category; 
use App\Models\Product;

class SellerController extends Controller
{
    /**
     * Display the Seller Dashboard.
     */
    public function createProduct(): Response
    {
        $categories = Category::all();

        return Inertia::render('seller/products/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function storeProduct(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
        ]);

        // Get the authenticated user's store
        $store = Store::where('user_id', Auth::id())->firstOrFail();

        // Create the product attached to their store
        Product::create([
            'store_id' => $store->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            // Image upload can be added here later!
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Product added successfully!');
    }
    public function index(): Response
    {
        // Find the store belonging to the logged-in user
        $store = Store::where('user_id', Auth::id())->first();

        return Inertia::render('seller/Dashboard', [
            'store' => $store,
        ]);
    }

    /**
     * Register a new storefront for the seller.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:stores,name',
            'description' => 'required|string|max:1000',
        ]);

        Store::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Automatically makes URL-friendly slug
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Store created successfully!');
    }
}