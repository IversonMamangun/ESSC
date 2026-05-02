<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Order;

class BuyerController extends Controller
{
    /**
     * Display the My Purchases dashboard.
     */
    public function purchases(): Response
    {
        $user = Auth::user();

        // Fetch the user's orders, including the products and the store they belong to
        $rawOrders = Order::where('user_id', $user->id)
            ->with(['products.store']) // Loads the pivot table and store data
            ->latest()
            ->get();

        // Map the database data to match the exact format Vue is expecting
        $formattedOrders = $rawOrders->map(function ($order) {
            
            // Get the store name from the first product (assuming 1 store per order for now)
            $storeName = $order->products->first()->store->name ?? 'ESSC Marketplace';
            
            // Calculate total if not stored directly on the orders table
            $totalAmount = $order->total_amount ?? $order->products->sum(function($product) {
                return $product->pivot->quantity * $product->pivot->price_at_time;
            });

            return [
                'id' => 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT), // e.g., ORD-00012
                'store_name' => $storeName,
                'status' => $order->status ?? 'To Pay', 
                'total_amount' => $totalAmount,
                'created_at' => $order->created_at->format('M d, Y'),
                'items' => $order->products->map(function ($product) {
                    
                    // Smart Image Resolver
                    $image = '/assets/store/online-store.jpg';
                    if (!empty($product->images)) {
                        $firstImage = $product->images[0];
                        $image = str_starts_with($firstImage, 'http') ? $firstImage : '/storage/' . $firstImage;
                    }

                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'image' => $image,
                        'price' => (float) ($product->pivot->price_at_time ?? $product->price),
                        'quantity' => (int) $product->pivot->quantity,
                    ];
                }),
            ];
        });

        return Inertia::render('user/Purchases', [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
            'orders' => $formattedOrders,
        ]);
    }

    /**
     * Display the My Account settings page
     */
    public function account(): Response
    {
        return Inertia::render('user/Account', [
            'user' => Auth::user()->only('id', 'name', 'email', 'phone', 'avatar'),
        ]); 
    }

    /**
     * Update the user's basic profile and avatar
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Display the Address settings page
     */
    public function address(): Response
    {
        return Inertia::render('user/Address', [
            'user' => Auth::user()->only('name', 'avatar', 'address', 'city', 'province', 'zip'),
        ]); 
    }

    /**
     * Update the user's delivery address
     */
    public function updateAddress(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('address', 'city', 'province', 'zip'));

        return back()->with('success', 'Address updated successfully.');
    }
}