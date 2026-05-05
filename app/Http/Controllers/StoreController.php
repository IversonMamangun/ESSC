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
     * Display the main store homepage / index.
     */
    public function index(): Response
    {
        $topDeals = Product::with('store')
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $discoverItems = Product::with('store')
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
            ->latest()
            ->paginate(15);

        return Inertia::render('store/ShopProfile', [
            'store' => $store,
            'products' => $products,
        ]);
    }
}