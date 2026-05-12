<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function index(): Response
    {
        // NO MORE RANDOM: Only gets products explicitly marked as a Top Deal
        $topDeals = Product::with('store')
            ->where('stock', '>', 0)
            ->where('is_top_deal', true) // <-- The magic filter
            ->latest() // Shows the newest deals first
            ->limit(8)
            ->get();

        $discoverItems = Product::with('store')
            ->where('stock', '>', 0) 
            ->latest()
            ->paginate(10);

        return Inertia::render('store/Index', [
            'topDeals' => $topDeals,
            'discoverItems' => $discoverItems,
        ]);
    }
    
    public function shopProfile(int $id): Response
    {
        $store = Store::findOrFail($id);

        $products = Product::where('store_id', $store->id)
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(15);

        return Inertia::render('store/ShopProfile', [
            'store' => $store,
            'products' => $products,
        ]);
    }
}