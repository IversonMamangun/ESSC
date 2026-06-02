<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\CartResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart()->firstOrCreate();
        $cart->loadMissing([
            'items.productVariant.product.images',
            'items.productVariant.attributeValues.attribute'
        ]);

        return Inertia::render('shop/customer/cart/Index', [
            'cart' => new CartResource($cart)
        ]);
    }
}
