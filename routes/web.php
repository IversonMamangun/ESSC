<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\UserType;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Auth\RegisterController; 
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController; 
use App\Http\Controllers\Auth\GoogleAuthController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

    Route::inertia('/register', 'auth/Register')->name('register');
    Route::post('/register/initiate', [RegisterController::class, 'initiate'])->name('register.initiate');
    Route::post('/register/verify', [RegisterController::class, 'verify'])->name('register.verify');
    Route::post('/register/resend', [RegisterController::class, 'initiate'])->name('register.resend'); 
    Route::post('/register/complete', [RegisterController::class, 'complete'])->name('register.complete');
});

// dedicated guest shop routes
Route::prefix('shop')
->name('shop.')
->group(function () {


});

// dedicated customer shop routes (customer experience)
Route::middleware([
    'auth', 
    'role:' . UserType::CUSTOMER
])
->prefix('shop')
->name('shop.')
->group(function () {


});

// dedicated seller routes
Route::middleware([
    'auth', 
    'role:' . UserType::SELLER
])
->prefix('seller')
->name('seller.')
->group(function () {


});

// dedicated admin routes
Route::middleware([
    'auth', 
    'role:' . UserType::ADMIN
])
->prefix('admin')
->name('admin.')
->group(function () {


});


// ???
Route::middleware('auth')->group(function () {
    // --- BUYER ROUTES ---
    Route::get('/purchases', [BuyerController::class, 'purchases'])->name('buyer.purchases');
    Route::patch('/buyer/orders/{order}/complete', [BuyerController::class, 'completeOrder'])->name('buyer.orders.complete');
    Route::patch('/buyer/orders/{order}/cancel', [BuyerController::class, 'cancelOrder'])->name('buyer.orders.cancel');
    
    Route::get('/account', [BuyerController::class, 'account'])->name('buyer.account');
    Route::post('/account/profile', [BuyerController::class, 'updateProfile'])->name('buyer.profile.update');
    
    Route::get('/account/address', [BuyerController::class, 'address'])->name('buyer.address');
    Route::post('/account/address', [BuyerController::class, 'storeAddress'])->name('buyer.address.store');
    
    // --- CHECKOUT ROUTES ---
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{tracking}', [CheckoutController::class, 'success'])->name('checkout.success');

});

Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/shop/{id}', [StoreController::class, 'shopProfile'])->name('shop.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// --- PUBLIC CART ROUTES ---
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow'); 
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// --- SELLER ROUTES ---
Route::middleware(['auth'])->group(function () {

    Route::get('/seller/dashboard', [SellerController::class, 'index'])
        ->name('seller.dashboard');

    Route::post('/seller/store', [SellerController::class, 'store'])
        ->name('seller.store.create');

    Route::patch('/seller/orders/{order}/status', [SellerController::class, 'updateOrderStatus'])
        ->name('seller.orders.status');

    Route::get('/seller/products/create', [SellerController::class, 'createProduct'])
        ->name('seller.products.create');

    Route::post('/seller/products', [SellerController::class, 'storeProduct'])
        ->name('seller.products.store');

    Route::get('/seller/products/{product}/edit', [SellerController::class, 'editProduct'])
        ->name('seller.products.edit');

    Route::post('/seller/products/{product}', [SellerController::class, 'updateProduct'])
        ->name('seller.products.update');

    Route::delete('/seller/products/{product}', [SellerController::class, 'destroyProduct'])
        ->name('seller.products.destroy');
});

require __DIR__.'/settings.php';