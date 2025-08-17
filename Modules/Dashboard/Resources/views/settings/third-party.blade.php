@extends('dashboard::layouts.master')
@section('dashboardTitle','تنظیمات سرویس‌های افزودنی')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <x-dashboard::breadcrumb :breadcrumb-name="trans('unit::units.units')"></x-dashboard::breadcrumb>--}}

        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.setting-menu')
            <!-- /Categories -->

            <div class="col-xl-9 col-lg-8 col-md-8">
                <div class="">
                    @if ($errors->any())
                        <div class="alert alert-warning">
                            <span>لطفا خطاهای زیر را بررسی نمایید</span>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="list-inline">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @include('dashboard::partials.alert')

                @can('third_party_enamad_setting_update')
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4 >تنظیمات نماد اعتماد
                                </h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.enamad.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="e_symbol_code">کد نماد اعتماد</label>
                                    <textarea class="form-control"  name="e_symbol_code" id="e_symbol_code" >{{ $e_symbol_code }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan

                @can('third_party_samandehi_setting_update')
                    <div class="card overflow-hidden my-2">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4>تنظیمات نماد ساماندهی
                                </h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.samandehi.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="samandehi_code">کد ساماندهی</label>
                                    <textarea class="form-control" name="samandehi_code" id="samandehi_code" >{{ $samandehi_code }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan

                @can('third_party_mediaad_setting_update')
                    <div class="card overflow-hidden my-2">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4>تنظیمات مدیااد
                                </h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.mediaad.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="mediaad">کد SDK ریتارگتینگ</label>
                                    <input type="text" class="form-control" value="{{ $mediaad }}" name="mediaad" id="mediaad" >
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan

                @can('third_party_goftino_setting_update')
                    <div class="card overflow-hidden my-2">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4>تنظیمات گفتینو
                                </h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.goftino.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="goftino">شناسه گفتینو </label>
                                    <input type="text" class="form-control" value="{{ $goftino }}" name="goftino" id="goftino" >
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan

                @can('third_party_gtag_setting_update')
                    <div class="card overflow-hidden my-2">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4>تنظیمات گوگل آنالیتیکس
                                </h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.gtag.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="google_tracking_id">Tracking ID گوگل آنالیتیکس شما</label>
                                    <input type="text" class="form-control" value="{{ $google_tracking_id }}" name="google_tracking_id" id="google_tracking_id" >
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan

                @can('third_party_ippanel_setting_update')
                    <div class="card overflow-hidden my-2">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4>تنظیمات پنل پیامک ippanel</h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.ippanel.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="ippanel_api_key">کلید API</label>
                                    <input type="password" class="form-control" value="{{ $ippanel_api_key }}" name="ippanel_api_key" id="ippanel_api_key" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="ippanel_pattern_id">پترن ID ارسال کد</label>
                                    <input type="password" class="form-control" value="{{ $ippanel_pattern_id }}" name="ippanel_pattern_id" id="ippanel_pattern_id" >
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan

                @can('third_party_zibal_setting_update')
                    <div class="card overflow-hidden my-2">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4>تنظیمات درگاه زیبال</h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.zibal.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="zibal_api_key">کلید API</label>
                                    <input type="password" class="form-control" value="{{ $zibal_api_key }}" name="zibal_api_key" id="zibal_api_key" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="zibal_merchant_id">شناسه مرچنت</label>
                                    <input type="password" class="form-control" value="{{ $zibal_merchant_id }}" name="zibal_merchant_id" id="zibal_merchant_id" >
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="zibal_wallet_id">شناسه کیف پول</label>
                                    <input type="password" class="form-control" value="{{ $zibal_wallet_id }}" name="zibal_wallet_id" id="zibal_wallet_id" >
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan

                @can('third_party_telegram_setting_update')
                    <div class="card overflow-hidden my-2">
                        <div class="card-body">
                            <div class="card-header p-0">
                                <h4>تنظیمات اطلاع‌رسانی تلگرام</h4>
                            </div>
                            <form method="POST" action="{{ route('dashboard.setting.third-party.telegram.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label" for="telegram_chat_id">CHAT ID</label>
                                    <input type="text" class="form-control" value="{{ $telegram_chat_id }}" name="telegram_chat_id" id="telegram_chat_id" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="telegram_bot_token">توکن ربات تلگرام</label>
                                    <input type="text" class="form-control" value="{{ $telegram_bot_token }}" name="telegram_bot_token" id="telegram_bot_token" >
                                </div>
                                <button type="submit" class="btn btn-primary">ذخیره</button>
                            </form>
                        </div>
                    </div>
                @endcan


            </div>
        </div>
    </div>
@endsection
