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
    ->group(function (){
        Route::get('/units','UnitController@index')->name('units.index')->can('units_view');
    });
