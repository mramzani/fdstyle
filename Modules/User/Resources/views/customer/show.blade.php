@extends('dashboard::layouts.master')
@section('dashboardTitle','اطلاعات مشتری')

@section('styles')

@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

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
                                    <h5 class="mb-2">{{ $customer->full_name }}</h5>
                                    <span class="badge bg-label-info">{{ number_format($customer->detail->due_amount) }}</span>
                                    <span class="badge bg-label-secondary">{{ $customer->detail->due_amount > 0 ? 'بدهکار' : 'بی‌حساب' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around flex-wrap my-2 py-3">
                            <div class="d-flex align-items-center  mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ $customer->detail->sales_order_count }}</h5>
                                    <span>تعداد کل خرید</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ $customer->detail->sales_return_count }}</h5>
                                    <span>تعداد خرید برگشتی</span>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-around flex-wrap my-2 py-3">
                            <div class="d-flex align-items-center mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ number_format($customer->detail->total_amount) }} تومان </h5>
                                    <span>کل مبلغ خرید</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center  mt-3 gap-3">
                                <span class="badge bg-label-primary p-2 rounded mt-1"><i class="bx bx-info-square bx-sm"></i></span>
                                <div>
                                    <h5 class="mb-0">{{ number_format($customer->detail->paid_amount) }} تومان </h5>
                                    <span>کل مبلغ پرداختی</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-2 border-bottom mb-4 secondary-font">جزئیات</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold me-2">موبایل:</span>
                                    <span>{{ $customer->mobile }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">ایمیل:</span>
                                    <span>{{ $customer->email ?? 'ندارد' }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">کدملی:</span>
                                    <span>{{ $customer->national_code ?? 'ندارد'}}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">وضعیت:</span>
                                    <span>{!! $customer->status_for_human !!}</span>
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

                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#sales-list" aria-controls="navs-pills-top-home" aria-selected="true">
                                <i class="bx bx-basket me-1"></i>لیست فروش
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cart-list" aria-controls="navs-pills-top-profile" aria-selected="false">
                                <i class="bx bx-basket me-1"></i>سبد خرید مشتری
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="sales-list" role="tabpanel">
                            <div class="table-responsive">
                                <h5 class="">فاکتور‌های فروش به مشتری</h5>
                                @if($customer->sale->isNotEmpty())
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
                                        @foreach($customer->sale as $sale)
                                            <tr>
                                                <td>{{ $sale->invoice_number }}</td>
                                                <td>{{ $sale->date_time }}</td>
                                                {{--<td>{{ $sale->payment->payMode->display_name ?? 'نامشخص' }}</td>--}}
                                                <td>{{ number_format($sale->total) }}</td>
                                                <td>{{ number_format($sale->paid_amount) }}</td>
                                                <td>{!! $sale->badge_payment_status !!}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-info m-2">تاکنون برای مشتری فروش ثبت نشده است.</div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="cart-list" role="tabpanel">
                            <div class="table-responsive">
                                @if($customer->cart != null)
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                        <tr>
                                            <th class="text-nowrap">نام محصول</th>
                                            <th class="text-nowrap text-center">تعداد</th>
                                            <th class="text-nowrap text-center">قیمت</th>
                                            <th class="text-nowrap text-center">جمع کل</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(unserialize($customer->cart->content) as $item)
                                            @php
                                                $product = \Modules\Product\Entities\Product::findOrFail($item->id);
                                            @endphp
                                            <tr>
                                                <td class="text-nowrap">{{ $item->name }}</td>
                                                <td>
                                                    {{ $item->qty }}
                                                </td>
                                                <td>
                                                    @if($product->detail->isActivePromotion())
                                                        {{ number_format($product->detail->promotion_price) }}
                                                    @else
                                                        {{ number_format($item->price) }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($product->detail->isActivePromotion())
                                                        {{ number_format($product->detail->promotion_price * $item->qty) }}
                                                    @else
                                                        {{ number_format($item->subtotal) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                        </div>
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
