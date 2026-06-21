<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\CompleteRegistrationRequest;
use Illuminate\Support\Facades\Auth;
use App\Actions\Auth\CreateVerifiedUser;

class RegistrationController extends Controller
{
    public function store(CompleteRegistrationRequest $request, CreateVerifiedUser $action) 
    {
        $user = $action->create(
            $request->validated()
        );

        Auth::login($user);

        return response()->json([
            'message' => 'Registration successful.',
            'redirect' => route('shop.home'),
        ]);
    }
}
