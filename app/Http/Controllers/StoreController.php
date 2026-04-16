<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class StoreController extends Controller
{
    public function index(): Response
    {
        // 1. Fetch Top Deals (e.g., get 16 random or latest products)
        // In a real app, you might filter by a 'is_featured' column
        $topDeals = Product::with('store')->take(16)->get();

        // 2. Fetch Discover Items with Laravel's built-in pagination (20 per page)
        $discoverItems = Product::with('store')->paginate(20);

        // 3. Send the data to the Vue component
        return Inertia::render('store/Index', [
            'topDeals' => $topDeals,
            'discoverItems' => $discoverItems,
        ]);
    }
}