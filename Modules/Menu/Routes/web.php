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


Route::prefix('dashboard')
    ->middleware(['web','auth:admin'])
    ->as('dashboard.')
    ->group(function() {
        Route::get('menus',[\Modules\Menu\Http\Controllers\MenuController::class,'index'])->name('menus.index')->can('view_front_menu');
        Route::post('menus',[\Modules\Menu\Http\Controllers\MenuController::class,'store'])->name('menus.store')->can('update_front_menu');
    });
