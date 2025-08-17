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
use Modules\Dashboard\Http\Controllers\Setting\GeneralSettingsController;
use Modules\Dashboard\Http\Controllers\Setting\ProductSettingController;
use Modules\Dashboard\Http\Controllers\Setting\ShippingSettingsController;
use Modules\Dashboard\Http\Controllers\Setting\ThirdPartySettingsController;

Route::prefix('dashboard')->middleware(['web', 'auth:admin'])->as('dashboard.')->group(function () {
    Route::get('/', 'DashboardController@index')->name('index')->middleware('can:dashboard_view_menu');
    Route::get('/company', 'CompanyController@edit')->name('company.edit')->middleware('can:companies_view');
    Route::post('/company', 'CompanyController@update')->name('company.update')->middleware('can:companies_edit');

    //---------- Setting Route -----------------
    Route::get('setting/general',[GeneralSettingsController::class,'show'])->name('setting.general')->middleware('can:general_setting_view');
    Route::put('setting/general',[GeneralSettingsController::class,'update'])->name('setting.general.update')->middleware('can:general_setting_update');

    Route::get('setting/shipping',[ShippingSettingsController::class,'edit'])->name('setting.shipping.edit')->middleware('can:shipping_setting_view');
    Route::put('setting/shipping',[ShippingSettingsController::class,'update'])->name('setting.shipping.update')->middleware('can:shipping_setting_update');

    Route::get('setting/third-party',[ThirdPartySettingsController::class,'edit'])->name('setting.third-party.edit')->middleware('can:third_party_setting_view');
    Route::put('setting/third-party/enamad',[ThirdPartySettingsController::class,'updateEnamad'])->name('setting.third-party.enamad.update')->middleware('can:third_party_enamad_setting_update');
    Route::put('setting/third-party/samandehi',[ThirdPartySettingsController::class,'updateSamandehi'])->name('setting.third-party.samandehi.update')->middleware('can:third_party_samandehi_setting_update');
    Route::put('setting/third-party/mediaad',[ThirdPartySettingsController::class,'updateMediaad'])->name('setting.third-party.mediaad.update')->middleware('can:third_party_mediaad_setting_update');
    Route::put('setting/third-party/goftino',[ThirdPartySettingsController::class,'updateGoftino'])->name('setting.third-party.goftino.update')->middleware('can:third_party_goftino_setting_update');
    Route::put('setting/third-party/gtag',[ThirdPartySettingsController::class,'updateGtag'])->name('setting.third-party.gtag.update')->middleware('can:third_party_gtag_setting_update');
    Route::put('setting/third-party/ippanel',[ThirdPartySettingsController::class,'updateIpPanel'])->name('setting.third-party.ippanel.update')->middleware('can:third_party_ippanel_setting_update');
    Route::put('setting/third-party/zibal',[ThirdPartySettingsController::class,'updateZibal'])->name('setting.third-party.zibal.update')->middleware('can:third_party_zibal_setting_update');
    Route::put('setting/third-party/telegram',[ThirdPartySettingsController::class,'updateTelegram'])->name('setting.third-party.telegram.update')->middleware('can:third_party_telegram_setting_update');
    //---------- Product Setting Route -----------------
    Route::get('setting/product',[ProductSettingController::class,'edit'])->name('setting.product.edit');
    Route::put('setting/product',[ProductSettingController::class,'update'])->name('setting.product.update');
    //---------- guarantee Route ----------------------
    Route::resource('guarantees',\GuaranteeController::class)->except(['show','create']);


});

/*Route::get('getProvince', function () {
    $provinces = \Illuminate\Support\Facades\Http::get('https://core.jibres.ir/r10/location/province?country=IR')->json();

    foreach ($provinces['result'] as $index => $province) {
        if ($index != 0) {
            \Modules\Dashboard\Entities\Province::create([
                'id' => (int) str_replace('IR-','',$province['id']),
                'name_fa' => $province['text'],
                'name_en' => $province['name'],
            ]);
        }
    }
});

Route::get('getCity', function () {

    $provinces = \Modules\Dashboard\Entities\Province::all();

    foreach ($provinces as $province) {
        if ($province->id <= 9) {
            $id = "IR-0" . $province->id;
        } else {
            $id = "IR-" . $province->id;
        }
        $cities = \Illuminate\Support\Facades\Http::get('https://core.jibres.ir/r10/location/city?province=' . $id)->json();

        foreach ($cities['result'] as $index => $city){
            if ($index != 0) {
               \Modules\Dashboard\Entities\City::create([
                   'province_id' => $province->id,
                   'name_fa' => $city['text'],
                   'name_en' => $city['name'],
               ]);
           }
        }
    }
});*/
