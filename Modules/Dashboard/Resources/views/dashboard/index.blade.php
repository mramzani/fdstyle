@extends('dashboard::layouts.master')
@section('dashboardTitle',config('dashboard.name'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/apex-charts/apex-charts.css') }}">
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="config('dashboard.name')"></x-dashboard::breadcrumb>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-user fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">38,566</h5>
                                    <small class="text-muted">کل فروش</small>
                                </div>
                            </div>
                            <div id="conversationChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-dollar fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">53,659</h5>
                                    <small class="text-muted">درآمد</small>
                                </div>
                            </div>
                            <div id="incomeChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-wallet fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">12,452</h5>
                                    <small class="text-muted">سود</small>
                                </div>
                            </div>
                            <div id="profitChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial rounded-circle bg-label-danger"><i class="bx bx-cart fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    <h5 class="card-title mb-0 me-2 primary-font">8,125</h5>
                                    <small class="text-muted">هزینه‌ها</small>
                                </div>
                            </div>
                            <div id="expensesLineChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-4 order-0">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">آمار خریدها و فروش‌ها</h5>
                    </div>
                    <div class="card-body">

                        <div id="analyticsBarChart"></div>
                    </div>
                </div>
            </div>
            <!-- Referral, conversion, impression & growth charts -->
            <div class="col-lg-6 col-md-12 order-0">
                <div class="row">
                    <!-- Impression Radial Chart-->
                    <div class="col-sm-12 col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">پرفروش‌ترین محصولات</h5>
                            </div>
                            <div class="card-body text-center">
                                <div id="impressionDonutChart" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row d-none">
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title mb-2">{{ user()->full_name }} عزیز خوش اومدی!</h3>

                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-12">
                                <h1 class="display-6 text-primary mb-2 pt-3 pb-2">امروز: {{ verta()->format('l %d F %Y') }}</h1>
                                <h1 class="display-6 text-primary mb-2 pt-3 pb-2">ساعت: {{ verta()->format('H:i') }}</h1>
                                <small class="d-block mb-3 lh-1-85"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/panel/js/cards-analytics.js') }}"></script>
@endsection
