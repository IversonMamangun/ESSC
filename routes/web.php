<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::inertia('/store', 'store/Index')->name('store.index');
Route::inertia('/product', 'store/Show')->name('product.show');
Route::inertia('/cart', 'store/Cart')->name('cart.index');
Route::inertia('/checkout', 'store/Checkout')->name('checkout.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';