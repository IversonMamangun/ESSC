<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Address; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $selectedIds = $request->query('selected_ids', []);

        if (empty($cart) || empty($selectedIds)) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout.');
        }

        $products = Product::whereIn('id', $selectedIds)->get();
        $subtotal = 0;
        $items = [];

        foreach ($products as $product) {
            if (!isset($cart[$product->id])) continue; 

            $qty = $cart[$product->id]['quantity'];
            $subtotal += $product->price * $qty;
            
            $items[] = [
                'name' => $product->title,
                'qty' => $qty,
                'price' => (float) $product->price * $qty,
            ];
        }

        $tax = $subtotal * 0.12; 
        $shipping = 150.00; 
        $total = $subtotal + $tax + $shipping;
        
        $user = Auth::user();

        return Inertia::render('store/Checkout', [
            'orderSummary' => [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'items' => $items,
            ],
            'selectedIds' => $selectedIds,
            
            'addresses' => $user->addresses()->orderByDesc('is_default')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'address_id' => 'required|exists:addresses,id',
            'paymentMethod' => 'required|string|in:credit_card,gcash,cod',
        ]);

        $user = Auth::user();
        
        $address = $user->addresses()->findOrFail($request->address_id);

        $cart = $request->session()->get('cart', []);
        $selectedIds = $request->selected_ids;

        $products = Product::whereIn('id', $selectedIds)->get();

        foreach ($products as $product) {
            $qty = $cart[$product->id]['quantity'];
            if ($product->stock < $qty) {
                return back()->withErrors([
                    'stock' => "Sorry, only {$product->stock} left for '{$product->title}'."
                ]);
            }
        }

        DB::transaction(function () use ($cart, $request, $products, $selectedIds, $address, $user) {
            $lockedProducts = Product::whereIn('id', $selectedIds)->lockForUpdate()->get();
            $subtotal = 0;
            $pivotData = [];

            foreach ($lockedProducts as $product) {
                $qty = $cart[$product->id]['quantity'];
                $subtotal += $product->price * $qty;
                
                $product->decrement('stock', $qty);

                $pivotData[$product->id] = [
                    'quantity' => $qty,
                    'price_at_time' => $product->price,
                ];
            }

            $tax = $subtotal * 0.12;
            $shipping = 150.00;
            $total = $subtotal + $tax + $shipping;

            $userEmail = $user->email ?? 'No Email Provided';

            $fullShippingAddress = sprintf(
                "[%s] %s | %s | %s, %s, %s %s (Account: %s)",
                $address->label,
                $address->recipient_name,
                $address->phone,
                $address->address,
                $address->city,
                $address->province,
                $address->zip,
                $userEmail 
            );

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'shipping_address' => $fullShippingAddress,
                'payment_method' => $request->paymentMethod,
                'status' => 'pending', 
                'tracking_number' => 'TRK-' . strtoupper(uniqid()), 
            ]);

            $order->products()->attach($pivotData);
        });

        foreach ($selectedIds as $id) {
            unset($cart[$id]);
        }
        $request->session()->put('cart', $cart);

        return redirect()->route('store.index')->with('success', 'Order placed successfully!');
    }
}