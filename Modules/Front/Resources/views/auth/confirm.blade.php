@extends('front::layouts.auth')
@section('title','تایید کد' .company()->site_title)
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
                        کد تایید را وارد کنید
                    </div>

                    <form action="{{ route('front.user.confirm.code') }}" method="post">
                        @csrf
                        <div class="form-element-row">
                            @error('invalid_code')
                            <div class="alert alert-warning">{{ $message }} <a href="{{ route('front.user.login') }}">تغییر شماره</a></div>
                            @enderror

                            <label for="phone-number" class="label-element">کد تایید برای
                                شماره {{ session()->get('mobile') }}
                                پیامک شد</label>
                            <div class="form-element-with-icon">
                                <input type="text" class="input-element" id="phone-number" name="code">
                                <i class="fad fa-mobile-alt"></i>
                            </div>
                            @error('code')
                            <div class="text-danger my-1">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                            @enderror
                        </div>


                        <div class="form-element-row text-left">
                            <button type="submit" class="btn-element btn-block btn-info-element">
                                <i class="fad fa-sign-in-alt"></i>
                                ورود
                            </button>

                        </div>


                    </form>

                </div>
            </div>
        </div>
    </main>

@endsection
