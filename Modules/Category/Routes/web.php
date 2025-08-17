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
    ->group(function() {
        Route::get('categories','CategoryController@index')->name('categories.index')->can('categories_view');
        Route::get('categories/create','CategoryController@create')->name('categories.create')->can('categories_create');
        Route::post('categories','CategoryController@store')->name('categories.store')->can('categories_create');
        Route::get('categories/{category}/edit','CategoryController@edit')->name('categories.edit')->can('categories_edit');
        Route::put('categories/{category}','CategoryController@update')->name('categories.update')->can('categories_edit');
        Route::delete('categories/{category}','CategoryController@destroy')->name('categories.destroy')->can('categories_delete');
});
