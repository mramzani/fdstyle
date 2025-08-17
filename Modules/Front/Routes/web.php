<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Front\Http\Controllers\AuthController;
use Modules\Front\Http\Controllers\BrandController;
use Modules\Front\Http\Controllers\CartController;
use Modules\Front\Http\Controllers\CategoryController;
use Modules\Front\Http\Controllers\HomeController;
use Modules\Front\Http\Controllers\OrderController;
use Modules\Front\Http\Controllers\ProductController;
use Modules\Front\Http\Controllers\ProfileController;
use Modules\Front\Http\Controllers\SiteMapController;

Route::get('sitemap-generate',[SiteMapController::class,'all']);

Route::as('front.')->group(function () {

    Route::get('logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('product/'. get_product_prefix() . '-{product}/{name?}', [ProductController::class, 'show'])->name('product.show');
    Route::get('cart/{product}/add',[CartController::class,'add'])->name('cart-product.add');
    //Route::get('cart/{product}/{variant}/add',[CartController::class,'add'])->name('cart-product-variant.add');
    Route::get('cart',[CartController::class,'all'])->name('cart-product.all');
    Route::get('cart/clear',[CartController::class,'clear'])->name('cart-product.clear');
    Route::get('category/{category:slug}',[CategoryController::class,'show'])->name('category.list');
    Route::get('brand/{brand:slug}',[BrandController::class,'show'])->name('brand.list');
    Route::get('search',[HomeController::class,'search'])->name('product.search');

    Route::middleware('customerActivation')->group(function () {
        Route::get('shipping',[CartController::class,'shipping'])->name('checkout.shipping');
        Route::get('get-city/{province_id}',[ProfileController::class,'getCity'])->name('get-city');
        Route::post('order/payment',[OrderController::class,'store'])->name('order.store');
        Route::get('payment/{gateway}/callback',[OrderController::class,'verify'])->name('payment.verify');

    });

    // Auth Route
    Route::middleware('guest:customer')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('user.show-login-form');
        Route::post('login', [AuthController::class, 'requestOtp'])->name('user.login');
        Route::get('confirm', [AuthController::class, 'confirmForm'])->name('user.confirm.form');
        Route::post('confirm', [AuthController::class, 'confirmCode'])->name('user.confirm.code');
    });

    Route::middleware('customerAuth')->group(function () {
        Route::get('profile', [ProfileController::class, 'index'])->name('user.profile');
        Route::get('profile/personal-info', [ProfileController::class, 'personalInfoFrom'])
            ->name('user.profile.personal-info-form');

        Route::post('profile/personal-info', [ProfileController::class, 'savePersonalInfo'])
            ->name('user.profile.personal-info-save');

        Route::get('profile/address', [ProfileController::class, 'addressList'])->name('user.profile.address.list');
        Route::get('profile/orders', [OrderController::class, 'list'])->name('user.orders');
        Route::get('profile/orders/{order}', [OrderController::class, 'show'])->name('user.orders.show');
        Route::get('profile/orders/{status}/get', [OrderController::class, 'getOrder'])->name('user.orders.status.get');
        Route::get('profile/sales', [OrderController::class, 'sales'])->name('user.sales');
        Route::get('profile/sales/{order}', [OrderController::class, 'show'])->name('user.sales.show');
    });
    //------------- Page Controller Route ---------------------
    Route::get('/page/{page:slug}',[\Modules\PageBuilder\Http\Controllers\PageController::class,'show'])->name('page.show');

});
