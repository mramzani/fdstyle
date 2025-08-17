@extends('front::layouts.app')
@section('title','خریدهای حضوری شما - ' .company()->site_title)
@section('title','profile')

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
                                تاریخچه خریدهای حضوری
                            </div>
                            <section class="border rounded-md px-2">

                                <section class="table--order mt-4">
                                    @include('front::partials.alert')
                                    <div class="table-responsive">
                                        <table class="table rounded-md">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>شماره سفارش</th>
                                                <th>تاریخ ثبت سفارش</th>
                                                <th>مبلغ پرداختی</th>
                                                <th>مبلغ کل</th>
                                                <th>وضعیت سفارش</th>
                                                <th>عملیات پرداخت</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($sales as $sale)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td class="order-code">{{ $sale->invoice_number }}</td>
                                                        <td>{{ $sale->date_time }}</td>
                                                        <td>{{ number_format($sale->paid_amount) }} تومان </td>
                                                        <td>{{ number_format($sale->total) }} تومان </td>
                                                        <td>{!! $sale->badge_order_status !!}</td>
                                                        <td class="order-detail-link">
                                                            <a href="{{ route('front.user.sales.show',$sale->id) }}" target="_blank">
                                                                <i class="far fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End of Main -->
@endsection
