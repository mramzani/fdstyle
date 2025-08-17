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
use Modules\Tax\Http\Controllers\TaxController;


Route::prefix('dashboard')
    ->middleware(['web','auth:admin'])
    ->as('dashboard.')
    ->group(function (){
        Route::get('taxes',[TaxController::class,'index'])->name('taxes.index')->can('taxes_view');
        Route::get('taxes/{tax}/edit',[TaxController::class,'edit'])->name('taxes.edit')->can('taxes_edit');
        Route::post('taxes',[TaxController::class,'store'])->name('taxes.store')->can('taxes_create');
        Route::put('taxes/{tax}',[TaxController::class,'update'])->name('taxes.update')->can('taxes_edit');
        Route::delete('taxes/{tax}/destroy',[TaxController::class,'destroy'])->name('taxes.destroy')->can('taxes_create');
    });
