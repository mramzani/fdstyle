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
    ->group(function (){
        //Route::resource('brands', 'BrandController',['except' => ['show']]);
        Route::get('brands','BrandController@index')->name('brands.index')->can('brands_view');
        Route::get('brands/create','BrandController@create')->name('brands.create')->can('brands_create');
        Route::post('brands','BrandController@store')->name('brands.store')->can('brands_create');
        Route::get('brands/{brand}/edit','BrandController@edit')->name('brands.edit')->can('brands_edit');
        Route::put('brands/{brand}','BrandController@update')->name('brands.update')->can('brands_edit');
        Route::delete('brands/{brand}','BrandController@destroy')->name('brands.destroy')->can('brands_delete');
    });
