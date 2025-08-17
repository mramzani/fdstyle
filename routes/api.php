<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WarehousesController;
use Examyou\RestAPI\Facades\ApiRoute;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '/v1'], function () {
    $options = [
        'as' => 'api'
    ];
    //Route::apiResource('/warehouses', 'WarehousesController',$options);
});


