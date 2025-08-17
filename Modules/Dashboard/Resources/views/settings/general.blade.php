@extends('dashboard::layouts.master')
@section('dashboardTitle','تنظیمات عمومی')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css') }}" />
@endsection
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
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.setting.general.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="short_name">نام کوتاه نمایشی</label>
                                <input type="text" class="form-control" id="short_name" name="short_name" value="{{ $short_name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="seo_description">توضیحات متا</label>
                                <textarea name="seo_description" class="form-control" id="seo_description" cols="30" maxlength="200" rows="3">{{ $seo_description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="telegram_link">لینک تلگرام</label>
                                <input type="text" class="form-control" id="telegram_link" name="telegram_link" value="{{ $telegram_link }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="instagram_link">لینک اینستاگرام</label>
                                <input type="text" class="form-control" id="instagram_link" name="instagram_link" value="{{ $instagram_link }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="twitter_link">لینک توئیتر</label>
                                <input type="text" class="form-control" id="twitter_link" name="twitter_link" value="{{ $twitter_link }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="facebook_link">لینک فیسبوک</label>
                                <input type="text" class="form-control" id="facebook_link" name="facebook_link" value="{{ $facebook_link }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="copy_right_text">متن کپی رایت پایین صفحه</label>
                                <input type="text" class="form-control" id="copy_right_text" name="copy_right_text" value="{{ $copy_right_text }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="otp_expiration_period">مدت زمان منقضی شدن کد پیامک (ثانیه)</label>
                                <input type="text" class="form-control" id="otp_expiration_period" name="otp_expiration_period" value="{{ $otp_expiration_period }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="general_customer_mobile">موبایل مشتری عمومی</label>
                                <input type="text" class="form-control" id="general_customer_mobile" name="general_customer_mobile" value="{{ $general_customer_mobile }}">
                                <small>در هنگام فروش از طریق صندوق فروشگاهی، درصورتی که مشتری عمومی شما با این شماره تعریف شده باشد <strong>پیامک تشکر ارسال نخواهد شد</strong>.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="admin_mobile">موبایل مدیر فروشگاه</label>
                                <input type="text" class="form-control" id="admin_mobile" name="admin_mobile" value="{{ $admin_mobile }}">
                                <small>در صورت وارد کردن شماره بعد از <strong>ثبت سفارش آنلاین پیامک اطلاع رسانی</strong> ارسال خواهد شد.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script src="{{ asset('assets/panel/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    <script>
        $("#seo_description").each(function() {
            $(this).maxlength({
                warningClass: "label label-success bg-success text-white",
                limitReachedClass: "label label-danger",
                separator: " از ",
                preText: "شما ",
                postText: " حرف مجاز را تایپ کرده اید",
                validate: true,
                threshold: +this.getAttribute("maxlength")
            });
        });
    </script>
@endsection
