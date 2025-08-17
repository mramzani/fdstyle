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

use Modules\PageBuilder\Http\Controllers\BannerController;
use Modules\PageBuilder\Http\Controllers\HomeController;
use Modules\PageBuilder\Http\Controllers\HomeItemController;
use Modules\PageBuilder\Http\Controllers\OfferController;
use Modules\PageBuilder\Http\Controllers\PageController;
use Modules\PageBuilder\Http\Controllers\SlidersController;

Route::prefix(config('dashboard.route_prefix'))
    ->middleware(config('dashboard.route_middleware'))
    ->as(config('dashboard.route_as_name'))
    ->group(function () {
        //------------- Slider controller Route ----------------
        Route::get('sliders',[SlidersController::class,'index'])->name('sliders.index')->middleware('can:sliders_view');
        Route::get('sliders/create',[SlidersController::class,'create'])->name('sliders.create')->middleware('can:sliders_create');
        Route::post('sliders/create',[SlidersController::class,'store'])->name('sliders.store')->middleware('can:sliders_create');
        Route::get('sliders/{slider}/edit',[SlidersController::class,'edit'])->name('sliders.edit')->middleware('can:sliders_edit');
        Route::post('sliders/{slider}/edit',[SlidersController::class,'update'])->name('sliders.update')->middleware('can:sliders_edit');
        Route::delete('sliders/{slider}/destroy',[SlidersController::class,'destroy'])->name('sliders.destroy')->middleware('can:sliders_delete');
        //------------- SliderItem controller Route ----------------
        Route::post('sliders/{slider}/itemCreate',[SlidersController::class,'storeItem'])->name('sliders.item.store')->middleware('can:slider_item_create');
        Route::post('sliders/{item}/itemDelete',[SlidersController::class,'destroyItem'])->name('sliders.item.destroy')->middleware('can:slider_item_delete');
        //------------- Banner controller Route --------------------
        Route::get('banners',[BannerController::class,'index'])->name('banners.index')->middleware('can:banners_view');
        Route::get('banners/create',[BannerController::class,'create'])->name('banners.create')->middleware('can:banners_create');
        Route::post('banners/create',[BannerController::class,'store'])->name('banners.store')->middleware('can:banners_create');
        Route::get('banners/{banner}/edit',[BannerController::class,'edit'])->name('banners.edit')->middleware('can:banners_edit');
        Route::post('banners/{banner}/edit',[BannerController::class,'update'])->name('banners.update')->middleware('can:banners_edit');
        Route::delete('banners/{banner}/destroy',[BannerController::class,'destroy'])->name('banners.destroy')->middleware('can:banners_delete');
        //------------ Homes Controller Route ---------------------
        Route::get('homes',[HomeController::class,'index'])->name('home.index')->middleware('can:homes_view');
        Route::get('homes/create',[HomeController::class,'create'])->name('home.create')->middleware('can:homes_create');
        Route::post('homes/create',[HomeController::class,'store'])->name('home.store')->middleware('can:homes_create');
        Route::get('homes/{home}/edit',[HomeController::class,'edit'])->name('home.edit')->middleware('can:homes_edit');
        Route::delete('homes/{home}/destroy',[HomeController::class,'destroy'])->name('home.destroy')->middleware('can:homes_delete');
        Route::post('homes/{home}/edit',[HomeController::class,'update'])->name('home.update')->middleware('can:homes_edit');
        Route::post('homes/getItem',[HomeController::class,'getItem'])->name('homes.getItem')->middleware('can:homes_view');
        //------------- HomeItem Controller Route ---------------------
        Route::post('home-item/{home}/create',[HomeItemController::class,'store'])->name('home-item.store')->middleware('can:home_item_create');
        Route::delete('home-item/{homeItem}/destroy',[HomeItemController::class,'destroy'])->name('home-item.destroy')->middleware('can:home_item_delete');
        //------------- Pages Controller Route ---------------------
        Route::get('pages',[PageController::class,'index'])->name('page.index');
        Route::get('pages/create',[PageController::class,'create'])->name('page.create');
        Route::post('pages/create',[PageController::class,'store'])->name('page.store');
        Route::get('pages/{page}/edit',[PageController::class,'edit'])->name('page.edit');
        Route::put('pages/{page}/edit',[PageController::class,'update'])->name('page.update');
        Route::delete('pages/{page}/destroy',[PageController::class,'destroy'])->name('page.destroy');
        //------------- Offers Controller Route ---------------------
        Route::get('offers',[OfferController::class,'index'])->name('offer.index');
        Route::get('offers/create',[OfferController::class,'create'])->name('offer.create');
        Route::post('offers/create',[OfferController::class,'store'])->name('offer.store');
        Route::get('offers/{offer}/edit',[OfferController::class,'edit'])->name('offer.edit');
        Route::put('offers/{offer}/edit',[OfferController::class,'update'])->name('offer.update');
        Route::delete('offers/{offer}/destroy',[OfferController::class,'destroy'])->name('offer.destroy');

        Route::post('offers/products',[OfferController::class,'products'])->name('offers.products');
    });

Route::get('promotion/{offer:slug}',[OfferController::class,'landing'])->name('offer.show');
