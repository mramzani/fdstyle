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

use Modules\User\Http\Controllers\Auth\LoginController;

Route::get('auth', [LoginController::class, 'showLoginForm'])->middleware('guest:admin');
Route::post('auth', [LoginController::class, 'login'])->name('auth.login')->middleware('guest:admin');
Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');


Route::prefix('dashboard')
    ->middleware(['web', 'auth:admin'])
    ->as('dashboard.')
    ->group(function () {
        Route::get('/customers', 'CustomerController@index')->name('customers.index')->can('customers_view');
        Route::get('/customers/{customer}', 'CustomerController@show')->name('customers.show')->can('customers_detail_view');


        Route::get('/suppliers', 'SupplierController@index')->name('suppliers.index')->can('suppliers_view');
        Route::get('/suppliers/{supplier}', 'SupplierController@show')->name('suppliers.show')->can('suppliers_detail_view');

        Route::get('/users', 'UserController@index')->name('users.index')->can('users_view');
        Route::get('/users/{user}', 'UserController@show')->name('users.show')->can('users_detail_view');

        Route::get('/profile', 'UserController@profile')->name('user.profile')->can('show_self_profile');
        Route::post('/profile', 'UserController@profileSave')->name('user.profile.save')->can('update_self_profile');

        Route::get('get-customer', [\Modules\User\Http\Controllers\CustomerController::class, 'getCustomer'])->name('customer.get-customer');
        Route::get('get-supplier', [\Modules\User\Http\Controllers\SupplierController::class, 'getSupplier'])->name('supplier.get-supplier');
    });

