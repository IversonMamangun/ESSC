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
    /**
     * Display the checkout page with totals.
     */
    public function index(Request $request): Response|\Illuminate\Http\RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get();
        $subtotal = 0;
        $items = [];

        foreach ($products as $product) {
            $qty = $cart[$product->id]['quantity'];
            $subtotal += $product->price * $qty;
            
            $items[] = [
                'name' => $product->title,
                'qty' => $qty,
                'price' => (float) $product->price * $qty,
            ];
        }

        $tax = $subtotal * 0.12; // Standard 12% tax
        $shipping = 150.00; // Flat rate shipping
        $total = $subtotal + $tax + $shipping;

        return Inertia::render('store/Checkout', [
            'orderSummary' => [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'items' => $items,
            ]
        ]);
    }

    /**
     * Process the order and save it to the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'paymentMethod' => 'required|string|in:credit_card,gcash,cod',
        ]);

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('store.index');
        }

        // DB Transaction ensures that if one step fails, nothing saves (prevents corrupted orders)
        DB::transaction(function () use ($cart, $request) {
            $products = Product::whereIn('id', array_keys($cart))->lockForUpdate()->get();
            $subtotal = 0;
            $pivotData = [];

            foreach ($products as $product) {
                $qty = $cart[$product->id]['quantity'];
                $subtotal += $product->price * $qty;
                
                // Deduct from stock
                $product->decrement('stock', $qty);

                // Prepare pivot table data (locking in the price)
                $pivotData[$product->id] = [
                    'quantity' => $qty,
                    'price_at_time' => $product->price,
                ];
            }

            $tax = $subtotal * 0.12;
            $shipping = 150.00;
            $total = $subtotal + $tax + $shipping;

            // 1. Create the Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'status' => 'pending', 
                'tracking_number' => 'TRK-' . strtoupper(uniqid()), 
            ]);

            // 2. Attach products to the pivot table
            $order->products()->attach($pivotData);
        });

        // 3. Clear the session cart after successful order
        $request->session()->forget('cart');

        return redirect()->route('store.index')->with('success', 'Order placed successfully!');
    }
}