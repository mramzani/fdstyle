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


use Modules\Coupon\Http\Controllers\Panel\CouponsController;

Route::prefix('coupon')->group(function() {
    Route::get('/', 'CouponController@index');
});

Route::as('front.')->group(function () {
    Route::middleware('customerActivation')->group(function () {
        Route::post('coupon/apply',[\Modules\Coupon\Http\Controllers\CouponController::class,'apply'])->name('coupon.apply');
        Route::post('coupon/forget',[\Modules\Coupon\Http\Controllers\CouponController::class,'forget'])->name('coupon.forget');
    });
});

Route::prefix('dashboard')->as('dashboard.')
    ->middleware(['web','auth:admin'])->group(function (){
    Route::resource('coupons',\Panel\CouponsController::class);
    Route::get('coupon/setting',[CouponsController::class,'setting'])->name('coupon.setting.index');
    Route::post('coupon/setting',[CouponsController::class,'updateSetting'])->name('coupon.setting.update');
});
