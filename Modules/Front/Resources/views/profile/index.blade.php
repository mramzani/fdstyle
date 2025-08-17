@extends('front::layouts.app')
@section('title','پروفایل - ' .company()->site_title)

@section('mainContent')
    <!-- Start of Main -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-5">
                @include('front::partials.profile-sidebar')
                <div class="col-lg-9 col-md-8">
                    <div class="row mb-4">
                        <div class="col-lg-12 mb-lg-0 mb-3">
                            <div class="section-title mb-2">
                                اطلاعات شخصی
                            </div>
                            <section class="bg-white border rounded-md overflow-hidden p-3">
                                <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <div class="text-sm text-muted">نام و نام خانوادگی:</div>
                                        <div class="text-dark font-weight-bold">{{ Helper::getCustomer()->full_name }}</div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div class="text-sm text-muted">پست الکترونیک:</div>
                                        <div class="text-dark font-weight-bold">{{ Helper::getCustomer()->email }}</div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div class="text-sm text-muted">شماره تلفن همراه:</div>
                                        <div class="text-dark font-weight-bold">{{ Helper::getCustomer()->mobile }}</div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div class="text-sm text-muted">کد ملی:</div>
                                        <div class="text-dark font-weight-bold">{{ Helper::getCustomer()->national_code }}</div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div class="text-sm text-muted">تاریخ عضویت:</div>
                                        <div class="text-dark font-weight-bold">{{ verta(Helper::getCustomer()->created_at)->formatDifference() }}</div>
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <div class="text-sm text-muted">وضعیت:</div>
                                        <div class="text-dark font-weight-bold">
                                            {!! Helper::getCustomer()->status_for_human !!}
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="text-center">
                                            <a href="{{ route('front.user.profile.personal-info-form') }}" class="link--with-border-bottom">
                                                <i class="far fa-edit"></i>
                                                ویرایش اطلاعات شخصی
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End of Main -->
@endsection
