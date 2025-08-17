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


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Coupon\Entities\Coupon;
use Modules\Product\Entities\Product;

Route::fallback(function () {
    abort(404);
});


Route::get('test-telegram', function () {
    $token = config('front.notification.telegram.bot_token');
    $id = config('front.notification.telegram.chat_id');

    $res = Http::get('https://api.rinofy.ir/telegram/send-message', [
            'chat_id' => $id,
            'token' => $token,
            'text' => 'این پیام برای تست پروکسی تلگرام ارسال شد.',
        ]);
        
        return $res->json();
});
Route::get('php', function () {
phpinfo();
});

Route::get('clear',function (){
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('clear-compiled');
});
