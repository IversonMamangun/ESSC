<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\UserAddressResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing('addresses');

        return Inertia::render('shop/customer/account/address/Index', [
            'user' => $user->only('name', 'phone', 'avatar'),
            'addresses' => UserAddressResource::collection($user->addresses)->resolve(),
        ]);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        return Inertia::render('shop/customer/account/address/Create', [
            'user' => $user->only('name', 'phone', 'avatar'),
        ]);
    }
}
