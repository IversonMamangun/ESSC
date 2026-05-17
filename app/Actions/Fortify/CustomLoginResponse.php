<?php

namespace App\Actions\Fortify;

use App\Models\UserType;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $route = match ($user->user_type_id) {
            UserType::ADMIN => route('admin.dashboard'),
            UserType::SELLER => route('seller.dashboard'),
            UserType::CUSTOMER => route('shop.home'),
            default => route('landing'),
        };

        return $request->wantsJson()
            ? response()->json([
                'two_factor' => false,
                'redirect' => $route,
            ])
            : redirect($route);
    }
}