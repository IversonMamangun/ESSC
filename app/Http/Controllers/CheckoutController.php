<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
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

        return Inertia::render('store/Checkout', [
            'orderSummary' => [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'items' => $items,
            ],
            'selectedIds' => $selectedIds,
            'user' => [
                'name' => Auth::user()->name ?? '',
                'phone' => Auth::user()->phone,
                'address' => Auth::user()->address,
                'city' => Auth::user()->city,
                'province' => Auth::user()->province,
                'zip' => Auth::user()->zip,
            ]
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'paymentMethod' => 'required|string|in:credit_card,gcash,cod',
        ]);

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

        DB::transaction(function () use ($cart, $request, $products, $selectedIds) {
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

            // SAFTEY CHECK: Handle users who registered via OTP and don't have an email yet
            $userEmail = Auth::user()->email ?? 'No Email Provided';

            $fullShippingAddress = sprintf(
                "%s | %s | %s, %s, %s %s (Account: %s)",
                $request->fullName,
                $request->phone,
                $request->address,
                $request->city,
                $request->province,
                $request->zip,
                $userEmail 
            );

            $order = Order::create([
                'user_id' => Auth::id(),
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