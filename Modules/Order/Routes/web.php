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

use Modules\Order\Http\Controllers\MerchantController;
use Modules\Order\Http\Controllers\OnlineOrderController;
use Modules\Order\Http\Controllers\OrderPaymentController;
use Modules\Order\Http\Controllers\PaymentController;
use Modules\Order\Http\Controllers\PosController;
use Modules\Order\Http\Controllers\PurchaseController;
use Modules\Order\Http\Controllers\SalesController;

Route::prefix('dashboard')
    ->middleware(['web', 'auth:admin'])
    ->as('dashboard.')
    ->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos')->can('pos_view');
        Route::post('/pos', [PosController::class, 'store'])->name('pos.store')->can('sales_create');

        Route::get('sales', [SalesController::class, 'index'])->name('sales.index')->can('sales_view');
        Route::get('sales/{sale}/edit', [SalesController::class, 'edit'])->name('sales.edit')->can('sales_edit');
        Route::post('sales/{sale}/edit', [SalesController::class, 'update'])->name('sales.update')->can('sales_edit');
        Route::delete('sales/{sale}', [SalesController::class, 'destroy'])->name('sales.destroy')->can('sales_delete');


        Route::post('sales/get-detail', [SalesController::class, 'getDetail'])->name('sales.get-detail')->can('sales_view');
        Route::post('order/payment', [OrderPaymentController::class, 'store'])->name('sales.payment.store')->can('order_payments_create');
        Route::get('payment/{payment_type}', [PaymentController::class, 'index'])->name('payment.index')->can('order_payments_view');
        Route::delete('payment/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy')->can('order_payments_delete');

        Route::get('purchases', [PurchaseController::class, 'index'])->name('purchase.index')->can('purchases_view');
        Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchase.create')->can('purchases_create');
        Route::post('purchases', [PurchaseController::class, 'store'])->name('purchase.store')->can('purchases_create');
        Route::get('purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchase.edit')->can('purchases_edit');
        Route::post('purchases/{purchase}/edit', [PurchaseController::class, 'update'])->name('purchase.update')->can('purchases_edit');
        Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchase.destroy')->can('purchases_delete');
        Route::post('purchases/get-detail', [PurchaseController::class, 'getDetail'])->name('purchase.get-detail')->can('purchases_view');


        Route::get('/orders',[OnlineOrderController::class,'index'])->name('online-order.index')->can('order_view');
        Route::get('/orders/{order}/show',[OnlineOrderController::class,'show'])->name('online-order.show')->can('order_details_view');
        //for change
        Route::post('/order-status/{order}',[OnlineOrderController::class,'changeOrderStatus'])->name('online-order.change-order-status')->can('change_order_status');
        Route::post('/order-payment-status/{order}',[OnlineOrderController::class,'changePaymentOrderStatus'])->name('online-order.change-payment-order-status')->can('change_order_payment_status');
        Route::post('/order-store-tracking-number/{order}',[OnlineOrderController::class,'storeTrackingNumber'])->name('online-order.number-tracking-store')->can('insert_order_tracking_number');


        Route::get('/transactions',[PaymentController::class,'transactions'])->name('transactions.list')->can('view_transaction');

        Route::get('/checkout',[MerchantController::class,'index'])->name('checkout.index')->can('view_merchants');
        Route::get('/checkout/{merchant}/request',[MerchantController::class,'request'])->name('checkout.request');
    });
