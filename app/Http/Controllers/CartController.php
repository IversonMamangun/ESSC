<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     */
    public function index(Request $request): Response
    {
        $cart = $request->session()->get('cart', []);
        
        // Fetch only the products that are currently in the session cart
        $products = Product::with('store')->whereIn('id', array_keys($cart))->get();

        // Format the data exactly as your Cart.vue expects it
        $cartItems = $products->map(function ($product) use ($cart) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'price' => (float) $product->price,
                'quantity' => $cart[$product->id]['quantity'],
                'image' => $product->image ?? '/assets/placeholder.png', 
                'store' => $product->store->name ?? 'Unknown Store',
            ];
        });

        return Inertia::render('store/Cart', [
            'cartItems' => $cartItems,
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);
        $productId = $validated['product_id'];

        // If item exists in cart, increase quantity. Otherwise, add it.
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $validated['quantity'];
        } else {
            $cart[$productId] = [
                'quantity' => $validated['quantity'],
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }
}