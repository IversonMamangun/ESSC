<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        $cart = $request->session()->get('cart', []);
        
        $products = Product::with('store')->whereIn('id', array_keys($cart))->get();

        $cartItems = $products->map(function ($product) use ($cart) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'price' => (float) $product->price,
                'quantity' => $cart[$product->id]['quantity'],
                'image' => $product->image ? '/storage/' . $product->image : '/assets/store/online-store.jpg', 
                'store' => $product->store->name ?? 'Unknown Store',
            ];
        });

        return Inertia::render('store/Cart', [
            'cartItems' => $cartItems,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $user = Auth::user();

        if ($user && $user->store && $product->store_id === $user->store->id) {
            return back()->with('error', 'You cannot add your own store\'s products to the cart.');
        }

        $cart = $request->session()->get('cart', []);
        $productId = $validated['product_id'];

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

    /**
     * Instantly add an item to the cart and jump straight to checkout (Buy Now).
     */
    public function buyNow(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $user = Auth::user();

        if ($user && $user->store && $product->store_id === $user->store->id) {
            return back()->with('error', 'You cannot buy your own store\'s products.');
        }

        $cart = $request->session()->get('cart', []);
        $productId = $validated['product_id'];

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $validated['quantity'];
        } else {
            $cart[$productId] = [
                'quantity' => $validated['quantity'],
            ];
        }

        $request->session()->put('cart', $cart);

        // Bypass the cart and go straight to checkout with this ID selected
        return redirect()->route('checkout.index', [
            'selected_ids' => [$productId]
        ]);
    }
    
    public function update(Request $request, $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $validated['quantity'];
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, $productId): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
        }

        return back()->with('success', 'Item removed.');
    }
}