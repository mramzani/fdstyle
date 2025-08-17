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

use Modules\Reports\Http\Controllers\ReportsController;

Route::prefix('dashboard/reports')
    ->middleware(['web', 'auth:admin'])
    ->name('dashboard.reports.')
    ->group(function () {
        Route::get('/profit-loss', [ReportsController::class, 'profitLoss'])->name('profit-loss')
            ->middleware('can:show_reports_profit_loss');
    });
