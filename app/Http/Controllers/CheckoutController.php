<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        // 1. Extract CartItem IDs sent from the Vue frontend
        $selectedIds = $this->extractSelectedIds($request);

        if (empty($selectedIds)) {
            return redirect()->route('shop.cart.index')->with('error', 'No items selected for checkout.');
        }

        $user = $request->user();

        // 2. Find the user's database cart record
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return redirect()->route('shop.cart.index')->with('error', 'Your cart is empty.');
        }

        // 3. Eager load the item variants and their parent products in one query
        $cartItems = CartItem::with(['productVariant.product'])
            ->where('cart_id', $cart->id)
            ->whereIn('id', $selectedIds) // Matches your Vue map((item) => item.id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.cart.index')->with('error', 'Selected items were not found in your cart.');
        }

        $subtotal = 0;
        $items = [];

        // 4. Calculate prices using the relationships
        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->productVariant;
            if (!$variant || !$variant->product) continue;

            $product = $variant->product;
            $qty = $cartItem->quantity;
            
            $subtotal += $product->price * $qty;
            
            $items[] = [
                'name' => $product->title . ($variant->name ? " - {$variant->name}" : ""),
                'qty' => $qty,
                'price' => (float) $product->price * $qty,
            ];
        }

        $tax = $subtotal * 0.12; 
        $shipping = 150.00; 
        $total = $subtotal + $tax + $shipping;
        
        return Inertia::render('shop/customer/checkout/Index', [
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
        $request->merge([
            'selected_ids' => $this->extractSelectedIds($request)
        ]);

        $request->validate([
            'selected_ids' => 'required|array',
            'address_id' => 'required|exists:user_addresses,id',
            'paymentMethod' => 'required|string|in:credit_card,gcash,cod',
        ]);

        $user = $request->user();
        $address = $user->addresses()->findOrFail($request->address_id);
        $selectedIds = $request->input('selected_ids');

        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart) {
            return redirect()->route('shop.cart.index')->with('error', 'Cart not found.');
        }

        // Fetch the cart items to be checked out
        $cartItems = CartItem::with(['productVariant.product'])
            ->where('cart_id', $cart->id)
            ->whereIn('id', $selectedIds)
            ->get();

        // Pre-transaction stock check
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->productVariant?->product;
            if (!$product) continue;

            if ($product->stock < $cartItem->quantity) {
                return back()->withErrors([
                    'stock' => "Sorry, only {$product->stock} left for '{$product->title}'."
                ]);
            }
        }

        $trackingNumber = null;

        DB::transaction(function () use ($cartItems, $request, $selectedIds, $address, $user, $cart, &$trackingNumber) {
            $subtotal = 0;
            $pivotData = [];

            foreach ($cartItems as $cartItem) {
                $variant = $cartItem->productVariant;
                if (!$variant) continue;
                
                // Lock the parent product row to safely decrement stock under concurrent traffic
                $product = Product::where('id', $variant->product_id)->lockForUpdate()->first();
                if (!$product) continue;

                $qty = $cartItem->quantity;
                $subtotal += $product->price * $qty;
                
                $product->decrement('stock', $qty);

                // Build pivot data for the order item record
                $pivotData[$product->id] = [
                    'quantity' => $qty,
                    'price_at_time' => $product->price,
                ];
            }

            $tax = $subtotal * 0.12;
            $shipping = 150.00;
            $total = $subtotal + $tax + $shipping;

            $fullShippingAddress = sprintf(
                "[%s] %s | %s | %s, %s, %s %s (Account: %s)",
                $address->label,
                $address->recipient_name,
                $address->phone,
                $address->address,
                $address->city,
                $address->province,
                $address->zip,
                $user->email ?? 'No Email Provided'
            );

            $trackingNumber = 'TRK-' . strtoupper(uniqid());

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'shipping_address' => $fullShippingAddress,
                'payment_method' => $request->paymentMethod,
                'status' => 'pending', 
                'tracking_number' => $trackingNumber, 
            ]);

            // Connects order items to your pivot table
            $order->products()->attach($pivotData);

            // 5. Clean up your database: Delete only the items that were checked out
            CartItem::where('cart_id', $cart->id)
                ->whereIn('id', $selectedIds)
                ->delete();
        });

        return redirect()->route('shop.checkout.success', ['tracking' => $trackingNumber]);
    }

    public function success($tracking): Response
    {
        $order = Order::where('tracking_number', $tracking)
            ->where('user_id', request()->user()->id) 
            ->firstOrFail();

        return Inertia::render('shop/customer/checkout/Success', [
            'order' => [
                'tracking_number' => $order->tracking_number,
                'total_price' => (float) $order->total_price,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'created_at' => $order->created_at->format('F j, Y, g:i a'),
            ]
        ]);
    }

    private function extractSelectedIds(Request $request): array
    {
        $ids = $request->input('selected_ids', []);
        
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        
        return array_values(array_filter((array) $ids));
    }
}