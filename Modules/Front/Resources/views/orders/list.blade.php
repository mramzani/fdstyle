@extends('front::layouts.app')
@section('title','سفارشات آنلاین شما')

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
                                تاریخچه سفارشات
                            </div>
                            <section class="border rounded-md p-3">
                                <div class="d-none d-sm-block">
                                    <ul class="nav nav-tabs" id="orders-tab" role="tablist">
                                        <li class="nav-item" role="presentation" onclick="filterProduct('pending_payment')">
                                            <a class="nav-link" id="pending-payment-tab" data-toggle="tab"
                                               href="#pending-payment" role="tab" aria-controls="pending-payment"
                                               aria-selected="true">
                                                در انتظار پرداخت
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation" onclick="filterProduct('processing,preparing,shipping')">
                                            <a class="nav-link active" id="pain-in-progress-tab" data-toggle="tab"
                                               href="#pain-in-progress" role="tab" aria-controls="pain-in-progress"
                                               aria-selected="false">
                                               جاری
                                                {{--<span class="badge badge-secondary">{{ count($orders) }}</span>--}}
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation" onclick="filterProduct('delivered')">
                                            <a class="nav-link" id="delivered-tab" data-toggle="tab"
                                               href="#delivered" role="tab" aria-controls="delivered"
                                               aria-selected="false">
                                                تحویل داده شده
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation" onclick="filterProduct('returned')">
                                            <a class="nav-link" id="returned-tab" data-toggle="tab" href="#returned"
                                               role="tab" aria-controls="returned" aria-selected="false">
                                                مرجوعی
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation" onclick="filterProduct('cancelled')">
                                            <a class="nav-link" id="canceled-tab" data-toggle="tab" href="#cancelled"
                                               role="tab" aria-controls="cancelled" aria-selected="false">
                                                لغو شده
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="d-sm-none tab-responsive-order-list">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle btn-block" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            لیست سفارشات بر اساس
                                        </button>
                                        <div class="dropdown-menu shadow-around w-100"
                                             aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" data-toggle="tab" href="#pending-payment"
                                               onclick="filterProduct('pending_payment')"
                                               role="tab" aria-controls="pending-payment" aria-selected="true">در
                                                انتظار پرداخت</a>
                                            <a class="dropdown-item" data-toggle="tab" href="#pain-in-progress"
                                               onclick="filterProduct('processing')"
                                               role="tab" aria-controls="pain-in-progress"
                                               aria-selected="false">جاری</a>
                                            <a class="dropdown-item" data-toggle="tab" href="#delivered" role="tab"
                                               onclick="filterProduct('delivered')"
                                               aria-controls="delivered" aria-selected="false">تحویل داده شده</a>
                                            <a class="dropdown-item" data-toggle="tab" href="#returned" role="tab"
                                               onclick="filterProduct('returned')"
                                               aria-controls="returned" aria-selected="false">مرجوعی</a>
                                            <a class="dropdown-item" data-toggle="tab" href="#canceled" role="tab"
                                               onclick="filterProduct('canceled')"
                                               aria-controls="canceled" aria-selected="false">لغو شده</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="pain-in-progress" role="tabpanel"
                                         aria-labelledby="pain-in-progress-tab">
                                        <div id="table-detail">
                                            @if(count($orders) > 0)
                                                <section class="table--order shadow-around mt-4">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>کد سفارش</th>
                                                                <th>تاریخ</th>
                                                                <th>مبلغ</th>
                                                                <th>وضعیت</th>
                                                                <th>عملیات</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($orders as $order)
                                                                <tr>
                                                                    <td>{{ $loop->index + 1  }}</td>
                                                                    <td class="order-code">{{ $order->invoice_number }}</td>
                                                                    <td>{{ $order->date_time }}</td>
                                                                    <td>{{ number_format($order->total) }} تومان</td>
                                                                    <td>{!! $order->online_order_status !!}</td>
                                                                    <td class="order-detail-link">
                                                                        <a href="{{ route('front.user.orders.show',$order->id) }}">
                                                                            <i class="far fa-chevron-left"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </section>
                                            @else
                                                <div class="empty-box">
                                                    <div class="icon">
                                                        <i class="fal fa-times-circle"></i>
                                                    </div>
                                                    <div class="message">
                                                        <p>سفارشی برای نمایش وجود نداره!</p>
                                                    </div>
                                                </div>
                                            @endif
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

@section('script')
    <script type="text/javascript">

        function filterProduct(status) {
            let url = "{{ route('front.user.orders.status.get',':status') }}";
            url = url.replace(':status', status);

            $.ajax({
                type: "GET",
                url: url,
                timeout: 3000,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $("#table-detail").html(response);
                },
                error: function (error) {
                    alert('خطا در دریافت اطلاعات');
                },
            });
        }

        $(document).ready(function () {
            RINO.ResponsiveTabOrderList();

        });
    </script>
@endsection
