<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Str;
use Inertia\Inertia;

class StoreController extends Controller
{
    
    public function create(Request $request)
    {
        $user = $request->user()->loadMissing(['store']);
        if ($user->store) {
            abort(403, 'You already have a store.');
        }

        return Inertia::render('seller/store/Create');
    }

    public function store(Request $request)
    {
        $user = $request->user()->loadMissing(['store']);
        if ($user->store) {
            abort(403, 'You already have a store.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        Store::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'is_active' => true,
        ]);

        return redirect()->route('seller.dashboard')
        ->with('success', 'Congratulations! Your store is now open.');
    }

}
