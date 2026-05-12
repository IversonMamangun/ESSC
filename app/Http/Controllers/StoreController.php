<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    /**
     * Display the main store index with Top Deals and Discover items.
     */
    public function index(): Response
    {
        // Gets products explicitly marked as a Top Deal (e.g., official ESSC products)
        $topDeals = Product::with('store')
            ->where('stock', '>', 0)
            ->where('is_top_deal', true)
            ->latest()
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
    
    /**
     * Display the specific store profile and its associated products.
     * Fixed the TypeError by removing the 'int' type hint.
     */
    public function shopProfile($id): Response
    {
        // Eloquent handles the conversion from string to integer automatically
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