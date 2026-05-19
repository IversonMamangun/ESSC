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

        return Inertia::render('seller/store/create');
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

        return redirect()->route('seller.dashboard');
    }
    
    public function edit(Store $store)
    {
        if ($store->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return Inertia::render('seller/store/Edit', [
            'store' => $store
        ]);
    }

    public function update(Request $request, Store $store)
    {
        if ($store->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'description' => 'required|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $store->description = $validated['description'];

        if ($request->hasFile('logo')) {
            $store->logo_path = $request->file('logo')->store('store_logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $store->cover_image_path = $request->file('cover_image')->store('store_covers', 'public');
        }

        $store->save();

        return redirect()->route('seller.dashboard')->with('success', 'Store profile updated successfully!');
    }
}