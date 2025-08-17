@extends('dashboard::layouts.master')
@section('dashboardTitle','گزارش کلی')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h4>گزارش کلی </h4>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    <li class="d-flex align-items-center mb-3 bg-label-primary p-2 rounded">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle">
                                <i class="bx bx-cube"></i>
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <p class="mb-0">مجموع مبلغ خرید</p>
                            </div>
                            <div class="item-progress">{{ number_format($purchases) }} تومان </div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-3 bg-label-info p-2 rounded">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle"><i class="bx bx-pie-chart-alt"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <p class="mb-0">مجموع مبلغ فروش</p>
                            </div>
                            <div class="item-progress">{{ number_format($sales) }} تومان </div>
                        </div>
                    </li>

                    <li class="d-flex align-items-center mb-3 bg-label-warning p-2 rounded">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle"><i class="bx bx-pie-chart-alt"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <p class="mb-0">مجموع ارزش محصولات موجود (خرید)</p>
                            </div>
                            <div class="item-progress">{{ number_format($product_purchase_value) }} تومان </div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-3 bg-label-danger p-2 rounded">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle"><i class="bx bx-pie-chart-alt"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <p class="mb-0">مجموع ارزش محصولات موجود (فروش)</p>
                            </div>
                            <div class="item-progress">{{ number_format($product_sales_value) }} تومان </div>
                        </div>
                    </li>

                    <li class="d-flex align-items-center mb-3 bg-label-success p-2 rounded">
                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle"><i class="bx bx-pie-chart-alt"></i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <p class="mb-0">مجموع سود خالص </p>
                            </div>
                            <div class="item-progress">{{ number_format($total_profit) }} تومان </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
