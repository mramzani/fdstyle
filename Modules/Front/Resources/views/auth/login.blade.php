@extends('front::layouts.auth')
@section('title','ورود یا ثبت نام ' .company()->site_title)
@section('content')
    <main class="page-content">
        <div class="container">
            <div class="auth-wrapper">
                <div class="auth-form shadow-around">
                    <div class="site-logo">
                        <a href="{{ route('front.home') }}">
                            <img src="{{ company()->image_url }}" width="{{ config('front.logo_width') }}" alt="{{ company()->site_title }}">
                        </a>
                    </div>
                    <div class="auth-form-title">
                        ورود | ثبت‌نام
                    </div>

                    <form action="{{ route('front.user.login') }}" method="post">
                        @csrf
                        <div class="form-element-row">
                            @include('front::partials.alert')
                            @error('request_otp_error')
                                <div class="alert alert-warning">{{ $message }}</div>
                            @enderror

                            <label for="phone-number" class="label-element">لطفا شماره موبایل خود را وارد کنید</label>
                            <div class="form-element-with-icon">
                                <input type="text" class="input-element" id="phone-number" name="mobile">
                                <i class="fad fa-mobile-alt"></i>
                            </div>
                            @error('mobile')
                            <div class="text-danger my-1">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>


                        <div class="form-element-row text-left">
                            <button type="submit" class="btn-element btn-block btn-info-element">
                                <i class="fad fa-sign-in-alt"></i>
                                دریافت کد
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </main>
@endsection
