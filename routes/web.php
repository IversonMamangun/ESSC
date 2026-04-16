<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Require login for cart and checkout
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    
    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Seller Dashboard Routes
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::post('/seller/store', [SellerController::class, 'store'])->name('seller.store.create');

    // Product Management for Sellers
    Route::get('/seller/products/create', [SellerController::class, 'createProduct'])->name('seller.products.create');
    Route::post('/seller/products', [SellerController::class, 'storeProduct'])->name('seller.products.store');
});

require __DIR__.'/settings.php';