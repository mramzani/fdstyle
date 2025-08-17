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

use Illuminate\Support\Facades\Route;
use Modules\Warehouse\Http\Controllers\WarehouseController;

Route::prefix('dashboard')
    ->middleware(['web','auth:admin'])
    ->as('dashboard.')
    ->group(function (){
    Route::get('warehouses',[WarehouseController::class,'index'])->name('warehouses.index')->can('warehouses_view');
    Route::post('warehouses',[WarehouseController::class,'store'])->name('warehouses.store')->can('warehouses_create');
    Route::get('warehouses/{warehouse}',[WarehouseController::class,'edit'])->name('warehouses.edit')->can('warehouses_edit');
    Route::put('warehouses/{warehouse}',[WarehouseController::class,'update'])->name('warehouses.update')->can('warehouses_edit');
    Route::delete('warehouses/{warehouse}',[WarehouseController::class,'destroy'])->name('warehouses.destroy')->can('warehouses_delete');
});
