<?php

use Illuminate\Http\Request;
use Modules\Front\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {
    Route::get('/torob/products',[ProductController::class,'index'])->name('api.v1.products');
    Route::get('/vibe/products',[ProductController::class,'vibe'])->name('api.v1.vibe.products');
    Route::get('/vibe/products/{product}',[ProductController::class,'vibeProduct'])->name('api.v1.vibe.products.show');
});
