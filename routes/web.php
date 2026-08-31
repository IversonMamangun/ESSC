<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Models\UserType;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Shop\HomeController as ShopHomeController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\StoreController as ShopStoreController;
use App\Http\Controllers\Shop\ProfileController as CustomerProfileController;
use App\Http\Controllers\Shop\ProfileVerificationController as CustomerProfileVerificationController;
use App\Http\Controllers\Shop\UserAddressController as CustomerUserAddressController;
use App\Http\Controllers\Shop\CartController as CustomerCartController;
use App\Http\Controllers\Shop\CheckoutController as CustomerCheckoutController;
use App\Http\Controllers\Shop\OrderController as CustomerOrderController;
use App\Http\Controllers\Shop\ReturnOrderController as CustomerReturnOrderController;
use App\Http\Controllers\Shop\ReviewController as CustomerReviewController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\StoreController as SellerStoreController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\SalesController as SellerSalesController;
use App\Http\Controllers\Seller\SalesAnalyticsController as SellerSalesAnalyticsController;
use App\Http\Controllers\Seller\ReviewController as SellerReviewController;
use App\Http\Controllers\Seller\ConversationController as SellerConversationController;
use App\Http\Controllers\Seller\MessageController as SellerMessageController;

Route::inertia('/', 'Welcome', [
    'canRegister' => true,
])->name('landing');

// dedicated only guest routes
Route::middleware('guest')
->group(function () {

    Route::inertia('/register', 'auth/Register')
        ->name('register');

    Route::post('/register', [RegistrationController::class, 'store'])
        ->name('register.store');
});

// both guest & auth routes (verifications logic)
Route::prefix('verifications')
->name('verifications.')
->group(function () {
    Route::post('/send', [VerificationController::class, 'send'])
        ->middleware('throttle:otp-send')
        ->name('send');

    Route::post('/verify', [VerificationController::class, 'verify'])
        ->middleware('throttle:otp-verify')
        ->name('verify');
});

// both guest & auth shop routes (public experience)
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
    Route::post('/account/profile/validate', [CustomerProfileController::class, 'validate'])
        ->name('account.profile.validate');
    Route::patch('/account/profile', [CustomerProfileController::class, 'update'])
        ->name('account.profile.update');
    Route::post('/account/profile/otp/send', [CustomerProfileVerificationController::class, 'send'])
        ->middleware('throttle:otp-send')
        ->name('account.profile.otp.send');
    Route::post('/account/profile/otp/verify', [CustomerProfileVerificationController::class, 'verify'])
        ->middleware('throttle:otp-verify')
        ->name('account.profile.otp.verify');

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
    Route::post('/checkout/buy-now', [CustomerCheckoutController::class, 'buyNow'])
        ->name('checkout.buy-now');
    Route::post('/checkout', [CustomerCheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::get('/orders', [CustomerOrderController::class, 'index'])
        ->name('orders.index');
    Route::get('/orders/{order:order_number}', [CustomerOrderController::class, 'show'])
        ->name('orders.show');
    Route::patch('/orders/{order:order_number}/complete', [CustomerOrderController::class, 'complete'])
        ->name('orders.complete');
    Route::patch('/orders/{order:order_number}/cancel', [CustomerOrderController::class, 'cancel'])
        ->name('orders.cancel');

    Route::get('/orders/{order:order_number}/return', [CustomerReturnOrderController::class, 'create'])
        ->name('orders.return.create');
    Route::post('/orders/{order:order_number}/return', [CustomerReturnOrderController::class, 'store'])
        ->name('orders.return.store');    

    Route::get('/orders/{order:order_number}/review', [CustomerReviewController::class, 'show'])
        ->name('orders.review.show');
    Route::get('/orders/{order:order_number}/review/create', [CustomerReviewController::class, 'create'])
        ->name('orders.review.create');
    Route::get('/orders/{order:order_number}/review/edit', [CustomerReviewController::class, 'edit'])
        ->name('orders.review.edit');
    Route::post('/orders/{order:order_number}/review', [CustomerReviewController::class, 'store'])
        ->name('orders.review.store');
    Route::patch('/orders/{order:order_number}/review', [CustomerReviewController::class, 'update'])
        ->name('orders.review.update');
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
    Route::get('/products/{product:slug}', [SellerProductController::class, 'show'])
        ->name('products.show');
    Route::post('/products', [SellerProductController::class, 'store'])
        ->name('products.store');
    Route::get('/products/{product:slug}/edit', [SellerProductController::class, 'edit'])
        ->name('products.edit');
    Route::post('/products/{product:slug}', [SellerProductController::class, 'update'])
        ->name('products.update');

    Route::get('/orders', [SellerOrderController::class, 'index'])
        ->name('orders.index');
    Route::get('/orders/{order:order_number}', [SellerOrderController::class, 'show'])
        ->name('orders.show');
    Route::patch('/orders/{order}/action', [SellerOrderController::class, 'action'])
        ->name('orders.action');
    Route::patch('/orders/{order}/cancel', [SellerOrderController::class, 'cancel'])
        ->name('orders.cancel');

    Route::get('/sales', [SellerSalesController::class, 'index'])
        ->name('sales.index');
    Route::patch('/sales/{order}/action', [SellerSalesController::class, 'action'])
        ->name('sales.action');
    Route::get('/sales/analytics', [SellerSalesAnalyticsController::class, 'analytics'])
        ->name('sales.analytics');

    Route::get('/reviews', [SellerReviewController::class, 'index'])
        ->name('reviews.index');
    Route::patch('reviews/{review}/reply', [SellerReviewController::class, 'reply'])
        ->name('reviews.reply');

    Route::get('/conversations', [SellerConversationController::class, 'index'])
        ->name('conversations.index');
    Route::get('/conversations/{conversation}', [SellerConversationController::class, 'show'])
        ->name('conversations.show');
    Route::post('/conversations/{conversation}/messages', [SellerMessageController::class, 'store'])
        ->name('conversations.messages.store');
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