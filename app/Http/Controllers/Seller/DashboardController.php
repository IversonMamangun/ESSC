<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\Seller\ProductResource;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing(['store']);

        if (! $user->store) {
            return redirect()->route('seller.store.create');
        }

        $products = Product::query()
            ->where('store_id', $user->store->id)
            ->with([
                'categories',
                'images',
                'variants.attributeValues.attribute',
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('seller/dashboard/Index', [
            'store' => $user->store,
            'products' => ProductResource::collection($products),
        ]);
        
    }
}
