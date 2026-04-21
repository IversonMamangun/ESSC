<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Store;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function index()
    {
        // 1. ADDED: ->with('store')
        $topDeals = Product::with('store')->inRandomOrder()->limit(8)->get();

        // 2. ADDED: ->with('store')
        $discoverItems = Product::with('store')->latest()->paginate(10);

        return Inertia::render('store/Index', [
            'topDeals' => $topDeals,
            'discoverItems' => $discoverItems,
        ]);
    }
    
    public function shopProfile($id)
    {
        // 1. Find the store by its ID (You will need to import App\Models\Store at the top!)
        $store = Store::findOrFail($id);

        // 2. Fetch only the products that belong to this specific store
        $products = Product::where('store_id', $store->id)
            ->latest()
            ->paginate(15);

        return Inertia::render('store/ShopProfile', [
            'store' => $store,
            'products' => $products,
        ]);
    }
}