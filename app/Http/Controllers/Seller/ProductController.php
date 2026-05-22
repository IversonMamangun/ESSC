<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\AttributeResource;
use App\Models\Category;
use App\Models\Attribute;
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

        return Inertia::render('seller/product/Create', [
            'categories' => CategoryResource::collection(
                Category::query()
                    ->whereNull('parent_id')
                    ->with('children')
                    ->get()
            )->resolve(),

            'attributes' => AttributeResource::collection(
                Attribute::with('values')->get()
            )->resolve(),
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
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
