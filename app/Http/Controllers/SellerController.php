<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SellerController extends Controller
{
    /**
     * Show the Seller Dashboard
     */
    public function index(): Response
    {
        // Fetch the logged-in seller's store and their products
        $store = Auth::user()->store;
        
        $products = [];
        if ($store) {
            $products = Product::where('store_id', $store->id)->latest()->get();
        }

        return Inertia::render('seller/Dashboard', [
            'store' => $store,
            'products' => $products,
        ]);
    }

    /**
     * Show the form to create a new product.
     */
    public function createProduct(): Response|RedirectResponse
    {
        // 1. Safety Check: Ensure the seller has created a store first
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('seller.dashboard')->with('error', 'You must set up your store before adding products.');
        }

        // 2. Fetch all categories so the seller can choose one from a dropdown
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
        // 1. Validate the form data (including the image file)
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB image
        ]);

        $store = Auth::user()->store;

        // 2. Handle the Image Upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            // This saves the image to storage/app/public/products
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 3. Save the product to the database
        Product::create([
            'store_id' => $store->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath,
        ]);

        // 4. Redirect back to the dashboard with a success message
        return redirect()->route('seller.dashboard')->with('success', 'Product published successfully!');
    }
}