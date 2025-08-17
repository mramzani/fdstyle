@extends('front::layouts.app')
@section('title','نتیجه پرداخت')
@section('mainContent')
    <!-- Page Content -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-4">
                <div class="col-xl-9 col-lg-8 col-md-8 mb-md-0 mb-3">
                    <div class="checkout-section shadow-around">
                        <div class="checkout-message">
                            @if($result['status']=="transaction.failed")
                                <div class="checkout-message-failed mb-3">
                                    <div class="icon-message failed-icon">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    سفارش <span class="order-code">{{ $result['order']->invoice_number }}</span> ثبت شد اما پرداخت ناموفق بود.
                                </div>
                                <div class="text-center text-muted">
                                    <p class="mb-1">
                                        برای جلوگیری از لغو سیستمی سفارش، تا ۱ ساعت آینده پرداخت را انجام دهید.
                                    </p>
                                    <p class="text-sm">
                                        چنانچه طی این فرایند مبلغی از حساب شما کسر شده است، طی ۷۲ ساعت آینده به حساب شما
                                        باز خواهد گشت.
                                    </p>
                                </div>

                            @endif
                            @if($result['status']=="transaction.success")
                                <div class="checkout-message-success mb-3">
                                    <div class="icon-message success-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    سفارش <span class="order-code">{{ $result['order']->invoice_number }}</span> با موفقیت پرداخت و ثبت گردید.
                                </div>
                                <div class="text-center text-muted">
                                    <p class="mb-4">
                                        سفارش شما با موفقیت ثبت و پرداخت با موفقیت انجام گردید.
                                        از اینکه ما را برای خرید انتخاب کردید از
                                        شما سپاسگزاریم.
                                    </p>
                                </div>
                            @endif
                            @if($result['status']=="transaction.approved")
                                <div class="checkout-message-success mb-3">
                                    <div class="icon-message success-icon">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    سفارش  <span class="order-code">{{ $result['order']->invoice_number }}</span> قبلا با موفقیت پرداخت و ثبت گردید.
                                </div>
                                <div class="text-center text-muted">
                                    <p class="mb-4">
                                        برای پیگیری سفارشات خود از بخش <a href="{{ route('front.user.orders') }}">سفارش‌های من</a> اقدام نمایید
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4">
                    <div class="shadow-around pt-3">
                        <div class="d-flex justify-content-between px-3 py-2">
                            <span class="text-muted">نام تحویل گیرنده:</span>
                            <span class="text-muted">
                                    {{ $result['order']->address->transferee }}
                                </span>
                        </div>
                        <div class="d-flex justify-content-between px-3 py-2">
                            <span class="text-muted">شماره تماس :</span>
                            <span class="text-danger">
                                    {{ $result['order']->address->mobile }}
                                </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between px-3 py-2">
                            <span class="font-weight-bold">مبلغ سفارش:</span>
                            <span class="font-weight-bold">
                                    {{ number_format($result['order']->payment->amount) }}
                                    <span class="text-sm">تومان</span>
                                </span>
                        </div>
                        <hr>
                        <div class="px-3 py-2">
                            <span class="text-muted d-block">آدرس :</span>
                            <span class="text-info">
                                    {{ $result['order']->address->full_address }}
                                </span>
                        </div>
                        <div class="px-3 py-4">
                            <a href="{{ route('front.user.orders') }}"
                               class="d-flex align-items-center justify-content-center px-4 py-2 btn btn-primary">پیگیری
                                سفارش <i class="fad fa-clipboard-list mr-3"></i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <!-- end Page Content -->
@endsection
