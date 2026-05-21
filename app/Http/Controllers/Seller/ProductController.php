<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user()->loadMissing(['store']);
        if (!$user->store) {
            abort(403, 'You do not have a store.');
        }

        return Inertia::render('seller/product/Create');
    }

    public function store(Request $request)
    {
        $user = $request->user()->loadMissing(['store']);
        if (!$user->store) {
            abort(403, 'You do not have a store.');
        }

        $request->validate([
            //
        ]);

        return redirect()->route('seller.dashboard')
        ->with('success', 'Product published successfully!');
    }
}
