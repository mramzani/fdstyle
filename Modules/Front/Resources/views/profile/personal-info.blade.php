@extends('front::layouts.app')
@section('title','ویرایش پروفایل - ' .company()->site_title)

@section('mainContent')
    <!-- Start of Main -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-5">
                @include('front::partials.profile-sidebar')
                <div class="col-lg-9 col-md-8">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="section-title mb-2">
                                اطلاعات شخصی
                            </div>

                            @if($errors->any())
                                <div class="my-2 border rounded-md p-2">
                                    <h6 class="p-1 text-danger">لطفا خطاهای زیر را بررسی کنید</h6>
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li class="text-danger my-2">
                                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                                <span> {{ $error }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <section class="shadow-around p-3">
                                @include('front::partials.alert')
                                <form action="{{ route('front.user.profile.personal-info-save') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 mb-5">
                                            <div class="text-sm text-muted mb-3">نام:</div>
                                            <div class="text-dark font-weight-bold">
                                                <div class="form-element-row mb-0">
                                                    <input type="text" class="input-element" name="first_name"
                                                           value="{{ Helper::getCustomer()->first_name ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-5">
                                            <div class="text-sm text-muted mb-3">نام خانوادگی:</div>
                                            <div class="text-dark font-weight-bold">
                                                <div class="form-element-row mb-0">
                                                    <input type="text" class="input-element" name="last_name"
                                                           value="{{ Helper::getCustomer()->last_name ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-5">
                                            <div class="text-sm text-muted mb-3">پست الکترونیک:</div>
                                            <div class="text-dark font-weight-bold">
                                                <div class="form-element-row mb-0">
                                                    <input type="text" class="input-element" name="email"
                                                           value="{{ Helper::getCustomer()->email ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-5">
                                            <div class="text-sm text-muted mb-3">موبایل:</div>
                                            <div class="text-dark font-weight-bold">
                                                <div class="form-element-row mb-0">
                                                    <input type="text" class="input-element" name="mobile" disabled value="{{ Helper::getCustomer()->mobile ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-5">
                                            <div class="text-sm text-muted mb-3">کد ملی:</div>
                                            <div class="text-dark font-weight-bold">
                                                <div class="form-element-row mb-0">
                                                    <input type="text" class="input-element" name="national_code" value="{{ Helper::getCustomer()->national_code ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-5">
                                            <div class="text-sm text-muted mb-3">تاریخ عضویت:</div>
                                            <div class="text-dark font-weight-bold">
                                                {{ verta(Helper::getCustomer()->created_at)->formatDifference() }}
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-element-row">
                                                <button type="submit" class="btn-element btn-success-element btn-block">
                                                    <i class="fad fa-user-edit"></i>
                                                    ذخیره تغییرات
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End of Main -->
@endsection
