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
use App\Http\Controllers\Shop\HomeController as ShopHomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\StoreController as ShopStoreController;
use App\Http\Controllers\Shop\ProfileController as CustomerProfileController;
use App\Http\Controllers\Shop\UserAddressController as CustomerUserAddressController;
use App\Http\Controllers\Shop\CartController as CustomerCartController;
use App\Http\Controllers\Shop\CheckoutController as CustomerCheckoutController;
use App\Http\Controllers\Shop\OrderController as CustomerOrderController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;

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
Route::name('shop.')
->group(function () {
    Route::get('/home', [ShopHomeController::class, 'index'])
        ->name('home');

    Route::get('/products', [ShopProductController::class, 'index'])
        ->name('products.index');   
    Route::get('/products/{product:slug}', [ShopProductController::class, 'show'])
        ->name('products.show');

    Route::get('/stores/{store:slug}', [ShopStoreController::class, 'show'])
        ->name('stores.show');
});

// dedicated customer shop routes (customer experience)
Route::middleware([
    'auth',
    'role:' . UserType::CUSTOMER
])
->name('shop.')
->group(function () {
    Route::get('/account/profile/edit', [CustomerProfileController::class, 'edit'])
        ->name('account.profile.edit');
    Route::patch('/account/profile', [CustomerProfileController::class, 'update'])
        ->name('account.profile.update');

    Route::get('/account/addresses', [CustomerUserAddressController::class, 'index'])
        ->name('account.addresses.index');
    Route::get('/account/addresses/create', [CustomerUserAddressController::class, 'create'])
        ->name('account.addresses.create');
    Route::post('/account/addresses', [CustomerUserAddressController::class, 'store'])
        ->name('account.addresses.store');
    Route::get('/account/addresses/{address}/edit', [CustomerUserAddressController::class, 'edit'])
        ->name('account.addresses.edit');
    Route::patch('/account/addresses/{address}', [CustomerUserAddressController::class, 'update'])
        ->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [CustomerUserAddressController::class, 'destroy'])
        ->name('account.addresses.destroy');

    Route::get('/cart', [CustomerCartController::class, 'index'])
        ->name('cart.index');
    Route::post('/cart/items', [CustomerCartController::class, 'store'])
        ->name('cart.items.store');
    Route::patch('/cart/items/{cartItem}', [CustomerCartController::class, 'update'])
        ->name('cart.items.update');
    Route::delete('/cart/items/{cartItem}', [CustomerCartController::class, 'destroy'])
        ->name('cart.items.destroy');

    Route::get('/checkout', [CustomerCheckoutController::class, 'index'])
        ->name('checkout.index');
    Route::post('/checkout/select', [CustomerCheckoutController::class, 'select'])
        ->name('checkout.select');
    Route::post('/checkout', [CustomerCheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/orders', [CustomerOrderController::class, 'index'])
        ->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
        ->name('orders.show');
});

// dedicated seller routes
Route::middleware([
    'auth', 
    'role:' . UserType::SELLER
])
->prefix('seller')
->name('seller.')
->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/store/create', [SellerStoreController::class, 'create'])
        ->name('store.create');
    Route::post('/store', [SellerStoreController::class, 'store'])
        ->name('store.store');
    Route::get('/store/{store:slug}/edit', [SellerStoreController::class, 'edit'])
        ->name('store.edit');
    Route::post('/store/{store:slug}', [SellerStoreController::class, 'update'])
        ->name('store.update');

    Route::get('/products', [SellerProductController::class, 'index'])
        ->name('products.index');
    Route::get('/products/create', [SellerProductController::class, 'create'])
        ->name('products.create');
    Route::post('/products', [SellerProductController::class, 'store'])
        ->name('products.store');
    Route::get('/products/{product:slug}/edit', [SellerProductController::class, 'edit'])
        ->name('products.edit');
    Route::post('/products/{product:slug}', [SellerProductController::class, 'update'])
        ->name('products.update');

    Route::get('/orders', [SellerOrderController::class, 'index'])
        ->name('orders.index');
    Route::patch('/orders/{order}/action', [SellerOrderController::class, 'action'])
        ->name('orders.action');
    Route::patch('/orders/{order}/cancel', [SellerOrderController::class, 'cancel'])
        ->name('orders.cancel');
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
// Route::middleware('auth')->group(function () {
//     // --- BUYER ROUTES ---
//     Route::get('/purchases', [BuyerController::class, 'purchases'])->name('buyer.purchases');
//     Route::patch('/buyer/orders/{order}/complete', [BuyerController::class, 'completeOrder'])->name('buyer.orders.complete');
//     Route::patch('/buyer/orders/{order}/cancel', [BuyerController::class, 'cancelOrder'])->name('buyer.orders.cancel');
    
//     Route::get('/account', [BuyerController::class, 'account'])->name('buyer.account');
//     Route::post('/account/profile', [BuyerController::class, 'updateProfile'])->name('buyer.profile.update');
    
//     Route::get('/account/address', [BuyerController::class, 'address'])->name('buyer.address');
//     Route::post('/account/address', [BuyerController::class, 'storeAddress'])->name('buyer.address.store');
    
//     // --- CHECKOUT ROUTES ---
//     Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
//     Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
//     Route::get('/checkout/success/{tracking}', [CheckoutController::class, 'success'])->name('checkout.success');

// });

// Route::get('/store', [StoreController::class, 'index'])->name('store.index');
// Route::get('/shop/{id}', [StoreController::class, 'shopProfile'])->name('shop.show');
// Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// // --- PUBLIC CART ROUTES ---
// Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
// Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
// Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow'); 
// Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
// Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// --- SELLER ROUTES ---
// Route::middleware(['auth'])->group(function () {

//     Route::get('/seller/dashboard', [SellerController::class, 'index'])
//         ->name('seller.dashboard');

//     Route::post('/seller/store', [SellerController::class, 'store'])
//         ->name('seller.store.create');

//     Route::patch('/seller/orders/{order}/status', [SellerController::class, 'updateOrderStatus'])
//         ->name('seller.orders.status');

//     Route::get('/seller/products/create', [SellerController::class, 'createProduct'])
//         ->name('seller.products.create');

//     Route::post('/seller/products', [SellerController::class, 'storeProduct'])
//         ->name('seller.products.store');

//     Route::get('/seller/products/{product}/edit', [SellerController::class, 'editProduct'])
//         ->name('seller.products.edit');

//     Route::post('/seller/products/{product}', [SellerController::class, 'updateProduct'])
//         ->name('seller.products.update');

//     Route::delete('/seller/products/{product}', [SellerController::class, 'destroyProduct'])
//         ->name('seller.products.destroy');
// });

require __DIR__.'/settings.php';