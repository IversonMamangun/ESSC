<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->loadMissing(['store']);

        if (! $user->store) {
            return redirect()->route('seller.store.create');
        }

        return Inertia::render('seller/dashboard/Index', [
            
        ]);
    }
}
