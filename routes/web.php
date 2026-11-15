<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\PublicAuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnerRegistrationController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about-us', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
Route::get('/products/{slug}', ProductPageController::class)->name('products.show');
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}/invoice', InvoiceController::class)->name('orders.invoice');
    Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::post('/account/shipping-addresses', [ShippingAddressController::class, 'store'])->name('account.shipping-addresses.store');
    Route::put('/account/shipping-addresses/{shippingAddress}', [ShippingAddressController::class, 'update'])->name('account.shipping-addresses.update');
    Route::delete('/account/shipping-addresses/{shippingAddress}', [ShippingAddressController::class, 'destroy'])->name('account.shipping-addresses.destroy');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [PublicAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [PublicAuthenticatedSessionController::class, 'store'])->name('login.store');
});
Route::post('/logout', [PublicAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::post('/contact/register', PartnerRegistrationController::class)
    ->middleware('throttle:partner-registrations')
    ->name('partner-registrations.store');
