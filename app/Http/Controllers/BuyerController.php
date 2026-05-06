<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Order;
use App\Models\Address; 

class BuyerController extends Controller
{
    public function purchases(): Response
    {
        $user = Auth::user();

        $rawOrders = Order::where('user_id', $user->id)
            ->with(['products.store']) 
            ->latest()
            ->get();

        $formattedOrders = $rawOrders->map(function ($order) {
            $storeName = $order->products->first()->store->name ?? 'ESSC Marketplace';
            
            $totalAmount = $order->total_amount ?? $order->products->sum(function($product) {
                return $product->pivot->quantity * $product->pivot->price_at_time;
            });

            return [
                'id' => 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT), 
                'store_name' => $storeName,
                'status' => $order->status ?? 'To Pay', 
                'total_amount' => $totalAmount,
                'created_at' => $order->created_at->format('M d, Y'),
                'items' => $order->products->map(function ($product) {
                    
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

    public function account(): Response
    {
        return Inertia::render('user/Account', [
            'user' => Auth::user()->only('id', 'name', 'email', 'phone', 'avatar'),
        ]); 
    }

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

    public function address(): Response
    {
        $user = Auth::user();

        return Inertia::render('user/Address', [
            'user' => $user->only('name', 'avatar'),
            'addresses' => $user->addresses()->orderByDesc('is_default')->latest()->get(),
        ]); 
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            $user->addresses()->update(['is_default' => false]);
        } 
        elseif ($user->addresses()->count() === 0) {
            $validated['is_default'] = true;
        }

        $user->addresses()->create($validated);

        return back()->with('success', 'New address added successfully.');
    }

    public function cancelOrder(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($order->status, ['pending', 'To Pay'])) {
            return back()->with('error', 'This order is already being processed and cannot be cancelled.');
        }

        $order->update(['status' => 'Cancelled']);

        return back()->with('success', 'Order cancelled successfully.');
    }

    public function completeOrder(Request $request, Order $order): \Illuminate\Http\RedirectResponse
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->update(['status' => 'Completed']);

        return back()->with('success', 'Order received and marked as complete!');
    }
}