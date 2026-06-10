<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CheckoutSelectRequest;
use App\Http\Resources\Shop\CheckoutItemResource;
use App\Http\Resources\Shop\PaymentMethodResource;
use App\Http\Resources\Shop\UserAddressResource;
use App\Models\CartItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function select(CheckoutSelectRequest $request) {
        $user = $request->user();

        $cartItems = CartItem::query()
            ->whereHas('cart', function ($query) use ($user) {
                $query->where(
                    'user_id',
                    $user->id
                );
            })
            ->whereIn(
                'id',
                $request->validated('cart_item_ids')
            )
            ->pluck('id');

        if ($cartItems->isEmpty()) {
            return back()->with(
                'error',
                'No valid cart items selected.'
            );
        }

        session([
            'checkout' => [
                'type' => 'cart',
                'cart_item_ids' => $cartItems->values()->all(),
            ]
        ]);

        return redirect()->route('shop.checkout.index');
    }

    public function index(Request $request)
    {
        $checkout = session('checkout');

        if (! $checkout) {
            return redirect()
                ->route('shop.cart.index');
        }

        $user = $request->user();

        $cartItems = CartItem::query()
            ->whereHas('cart', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn(
                'id',
                $checkout['cart_item_ids']
            )
            ->with([
                'productVariant.product.store',
                'productVariant.attributeValues.attribute',
            ])
            ->get();

        if ($cartItems->isEmpty()) {
            session()->forget('checkout');

            return redirect()
                ->route('shop.cart.index');
        }

        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        $paymentMethods = PaymentMethod::query()
            ->orderBy('name')
            ->get();

        $subtotal = $cartItems->sum(
            fn ($item) => $item->quantity * $item->productVariant->price
        );

        // temporary
        $shippingFee = $cartItems
            ->groupBy(
                fn ($item) => $item->productVariant->product->store_id
            )
            ->count() * 60;

        return Inertia::render(
            'shop/customer/checkout/Index',
            [
                'addresses' => UserAddressResource::collection($addresses)->resolve(),
                'paymentMethods' => PaymentMethodResource::collection($paymentMethods)->resolve(),
                'items' => CheckoutItemResource::collection($cartItems)->resolve(),
                'summary' => [
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'discount' => 0,
                    'total' => $subtotal + $shippingFee,
                ],
            ]
        );
    }
}