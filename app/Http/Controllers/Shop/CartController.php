<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\CartResource;
use App\Http\Requests\Shop\CartItemCreateRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart()->firstOrCreate();
        $cart->loadMissing([
            'items.productVariant.product.store',
            'items.productVariant.product.images',
            'items.productVariant.attributeValues.attribute'
        ]);

        return Inertia::render('shop/customer/cart/Index', [
            'cart' => new CartResource($cart)
        ]);
    }

    public function store(CartItemCreateRequest $request)
    {
        $user = $request->user();

        $variant = ProductVariant::query()
            ->with('product')
            ->findOrFail(
                $request->integer('product_variant_id')
            );

        if (! $variant->product->is_active) {
            return back()->with('error', 'Product is unavailable.');
        }

        if ($variant->stock <= 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        DB::transaction(function () use ($user, $variant, $request) {
            $cart = $user->cart()->firstOrCreate();
            $item = $cart->items()
                ->where(
                    'product_variant_id',
                    $variant->id
                )
                ->lockForUpdate()
                ->first();
            $requestedQty = $request->integer('quantity');

            if ($item) {
                $newQty = min(
                    $item->quantity + $requestedQty,
                    $variant->stock
                );
                $item->update([
                    'quantity' => $newQty,
                ]);
                return;
            }

            $cart->items()->create([
                'product_variant_id' => $variant->id,
                'quantity' => min(
                    $requestedQty,
                    $variant->stock
                ),
            ]);
        });

        return back()->with('success','Added to cart.');
    }
}
