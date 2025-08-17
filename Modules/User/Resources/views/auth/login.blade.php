@extends('user::layout.auth')

@section('authTitle',__('user::auth.login title'))
@section('content')
    <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
            <!-- Logo -->

            <!-- /Logo -->
            <h4 class="mb-2">ورود به حساب کاربری</h4>
            <p class="mb-4">ورود به بخش مدیریت سیستم</p>

            @error('mobile')
                @include('dashboard::partials.validation-error')
            @enderror

            <form id="formAuthentication" class="mb-3" action="{{ route('auth.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="mobile" class="form-label">موبایل</label>
                    <input type="text" class="form-control text-start" dir="ltr" id="mobile" name="mobile"
                           placeholder="موبایل خود را وارد کنید" autofocus>
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">رمز عبور</label>
                        <a href="#" class="d-none">
                            <small>رمز عبور را فراموش کردید؟</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control text-start" dir="ltr"
                               name="password"  >
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember_me" id="remember-me">
                        <label class="form-check-label" for="remember-me"> مرا بخاطر بسپار </label>
                    </div>
                </div>
                <button class="btn btn-primary d-grid w-100">ورود</button>
            </form>
        </div>
    </div>
@endsection
