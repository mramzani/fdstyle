@extends('dashboard::layouts.master')
@section('dashboardTitle','اطلاعات تامین‌کننده')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">کاربر / نمایش /</span> حساب
        </h4>
        <div class="row gy-4">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded my-4" src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" height="110" width="110" alt="User avatar">
                                <div class="user-info text-center">
                                    <h5 class="mb-2">{{ $supplier->full_name }}</h5>
                                    <span class="badge bg-label-info">{{ number_format(abs($supplier->detail->due_amount)) }}</span>
                                        @if($supplier->detail->due_amount > 0)
                                        <span class="badge bg-label-secondary">بدهکار</span>
                                        @elseif($supplier->detail->due_amount < 0)
                                        <span class="badge bg-label-secondary">بستانکار</span>
                                        @else
                                        <span class="badge bg-label-secondary">بی‌حساب</span>
                                        @endif

                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around flex-wrap my-2 py-3">
                            <div class="d-flex align-items-center  mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ $supplier->detail->purchase_order_count }}</h5>
                                    <span>تعداد کل فروش</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ $supplier->detail->purchase_return_count }}</h5>
                                    <span>تعداد فروش برگشتی</span>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-around flex-wrap my-2 py-3">
                            <div class="d-flex align-items-center mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ number_format(abs($supplier->detail->total_amount)) }} تومان </h5>
                                    <span>کل مبلغ فروش</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center  mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ number_format(abs($supplier->detail->paid_amount)) }} تومان </h5>
                                    <span>کل مبلغ پرداختی</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-2 border-bottom mb-4 secondary-font">جزئیات</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold me-2">موبایل:</span>
                                    <span>{{ $supplier->mobile }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">ایمیل:</span>
                                    <span>{{ $supplier->email ?? 'ندارد' }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">کدملی:</span>
                                    <span>{{ $supplier->national_code ?? 'ندارد'}}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">وضعیت:</span>
                                    <span>{!! $supplier->status_for_human !!}</span>
                                </li>


                            </ul>

                        </div>
                    </div>
                </div>
                <!-- /User Card -->
                <!-- Plan Card -->

                <!-- /Plan Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-basket me-1"></i>لیست خرید</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);"><i class="bx bx-arrow-back me-1"></i>برگشت از خرید</a>
                    </li>

                </ul>
                <!--/ User Pills -->

                <!-- Project table -->
                <div class="card mb-4">
                    <h5 class="card-header">فاکتور‌های خرید</h5>
                    <div class="table-responsive mb-3">

                        @if($supplier->purchase->isNotEmpty())
                            <table class="table datatable-project border-top">
                                <thead>
                                <tr>
                                    <th>شماره فاکتور</th>
                                    <th>تاریخ</th>
                                    {{--<th class="text-nowrap">نحوه پرداخت</th>--}}
                                    <th>کل مبلغ</th>
                                    <th>مبلغ پرداختی</th>
                                    <th>وضعیت پرداخت</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($supplier->purchase as $purchase)
                                    <tr>
                                        <td>{{ $purchase->invoice_number }}</td>
                                        <td>{{ $purchase->date_time }}</td>
                                        {{--<td>{{ $purchase->payment->payMode->display_name ?? 'نامشخص' }}</td>--}}
                                        <td>{{ number_format($purchase->total) }}</td>
                                        <td>{{ number_format($purchase->paid_amount) }}</td>
                                        <td>{!! $purchase->badge_payment_status !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info m-2">تاکنون برای مشتری فروش ثبت نشده است.</div>
                        @endif

                    </div>
                </div>
            </div>
            <!--/ User Content -->
        </div>

        <!-- Modal -->
        <!-- Edit User Modal -->

        <!--/ Edit User Modal -->


        <!-- /Modal -->
    </div>
@endsection
