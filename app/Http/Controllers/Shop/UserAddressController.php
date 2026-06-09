<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\UserAddressResource;
use App\Http\Requests\Shop\UserAddressCreateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Enums\UserAddressLabel;
use App\Models\UserAddress;

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

    public function store(UserAddressCreateRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        DB::transaction(function () use ($user, $validated) {

            $isFirstAddress = !$user->addresses()->exists();

            $user->addresses()->create([
                ...$validated,
                'is_default' => $isFirstAddress ? true : false
            ]);
        });

        return redirect()->route('shop.account.addresses.index')
            ->with('success', 'New address added successfully.');
    }

    public function destroy(Request $request, UserAddress $address)
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Address removed successfully.');
    }
}
