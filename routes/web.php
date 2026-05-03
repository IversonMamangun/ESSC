<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController; 
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController; // Added BuyerController
use App\Http\Controllers\Auth\GoogleAuthController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/shop/{id}', [StoreController::class, 'shopProfile'])->name('shop.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// --- PUBLIC CART ROUTES ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// --- GUEST ROUTES (Login & Registration) ---
Route::middleware('guest')->group(function () {
    
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

    // Standard Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    // OTP Login Routes
    Route::post('/login/otp', [LoginController::class, 'sendOtp'])->name('login.otp.send');
    Route::get('/login/verify-otp', [LoginController::class, 'showVerifyOtp'])->name('login.otp.verify');
    Route::post('/login/verify-otp', [LoginController::class, 'verifyOtp'])->name('login.otp.check');

    // OTP Registration Routes
    Route::inertia('/register', 'auth/Register')->name('register');
    Route::post('/register/initiate', [RegisterController::class, 'initiate'])->name('register.initiate');
    Route::post('/register/verify', [RegisterController::class, 'verify'])->name('register.verify');
    Route::post('/register/resend', [RegisterController::class, 'initiate'])->name('register.resend'); 
    Route::post('/register/complete', [RegisterController::class, 'complete'])->name('register.complete');
});

// --- THE TRAFFIC COP DASHBOARD ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->userType?->slug === 'buyer') {
            return redirect()->route('store.index');
        }

        if ($user->userType?->slug === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        // Fallback just in case
        return inertia('Dashboard');
    })->name('dashboard');
});

// --- PROTECTED ROUTES ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    
    // --- BUYER / CUSTOMER ROUTES ---
    Route::get('/purchases', [BuyerController::class, 'purchases'])->name('buyer.purchases');
    
    Route::get('/account', [BuyerController::class, 'account'])->name('buyer.account');
    Route::post('/account/profile', [BuyerController::class, 'updateProfile'])->name('buyer.profile.update');
    
    Route::get('/account/address', [BuyerController::class, 'address'])->name('buyer.address');
    Route::post('/account/address', [BuyerController::class, 'storeAddress'])->name('buyer.address.store');
    // --- CHECKOUT ROUTES ---
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // --- SELLER DASHBOARD ROUTES ---
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::post('/seller/store', [SellerController::class, 'store'])->name('seller.store.create');

    // --- PRODUCT MANAGEMENT FOR SELLERS ---
    Route::get('/seller/products/create', [SellerController::class, 'createProduct'])->name('seller.products.create');
    Route::post('/seller/products', [SellerController::class, 'storeProduct'])->name('seller.products.store');
});

require __DIR__.'/settings.php';