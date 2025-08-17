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
        Route::get('/roles','RoleController@index')->name('roles.index')->can('roles_view');
    });
