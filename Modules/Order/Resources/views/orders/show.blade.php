@extends('dashboard::layouts.master')
@section('dashboardTitle','جزئیات سفارش ' . $order->invoice_number)
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-preview">
            @include('dashboard::partials.alert')
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                <div class="card mb-3">
                    <div class="card-header border-bottom primary-font">
                        <ul class="nav nav-pills" role="tablist">
                            @can('order_details_view')
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#order-detail" aria-controls="order-detail"
                                            aria-selected="true">
                                        جزئیات سفارش
                                    </button>
                                </li>
                            @endcan
                            @can('order_histories')
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#order-history" aria-controls="order-history"
                                            aria-selected="false">
                                        تاریخچه‌سفارش
                                    </button>
                                </li>
                            @endcan
                            @can('order_cost')
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                            data-bs-target="#commissions" aria-controls="commissions"
                                            aria-selected="false">
                                        هزینه‌ها
                                    </button>
                                </li>
                            @endcan
                        </ul>
                    </div>
                    <div class="tab-content">
                        @can('order_details_view')
                            <div class="tab-pane fade show active" id="order-detail" role="tabpanel">
                                <div class="card-body">
                                    <div
                                        class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                                        <div class="mb-xl-0 mb-4">
                                            <div class="d-flex align-items-center svg-illustration mb-3 gap-2">
                                                <img src="{{ company()->image_url }}" class="rounded avatar" alt="">
                                                <span
                                                    class="app-brand-text h3 mb-0 fw-bold">{{ get_short_name() }}</span>
                                            </div>
                                            <p class="mb-1">آدرس فروشگاه : {{ company()->address }}</p>
                                            <p class="mb-0">
                                            <span class="d-inline-block"
                                                  dir="ltr">شماره تلفن: {{ company()->phone }}</span>
                                            </p>
                                        </div>
                                        <div>
                                            <h4>صورتحساب {{ $order->invoice_number }}</h4>
                                            <div class="mb-2 lh-1-85">
                                                <span class="me-1">تاریخ ثبت سفارش:</span>
                                                <span class="fw-semibold">{{ $order->date_time }}</span>
                                            </div>
                                            <div class="lh-1-85">
                                                <span class="me-1">وضعیت سفارش:</span>
                                                <span class="fw-semibold">{!! $order->order_status_for_panel !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-0">
                                <div class="card-body">
                                    <div class="row p-sm-3 p-0">

                                        <div class="col-xl-12 col-md-12 col-sm-7 col-12">
                                            <h6 class="pb-2">اطلاعات تحویل گیرنده: </h6>
                                            <table class="lh-2">
                                                <tbody>
                                                <tr>
                                                    <td class="pe-3">تحویل گیرنده:</td>
                                                    <td>{{ $order->address->transferee }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3">شماره تماس:</td>
                                                    <td>{{ $order->address->mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3">آدرس کامل:</td>
                                                    <td>{{ $order->address->full_address }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table border-top m-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>نام کالا</th>
                                            <th>کد</th>
                                            <th>تعداد</th>
                                            <th>قیمت واحد</th>
                                            <th>تخفیف واحد</th>
                                            <th>تخفیف کل</th>
                                            <th>مبلغ کل</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($order->products as $product)
                                            @php
                                                $unit_discount = $product->pivot->total_discount / $product->pivot->quantity;
                                            @endphp
                                            <tr>
                                                <td class="text-nowrap">{{ $loop->index + 1 }}</td>
                                                <td class="text-nowrap">{{ \Str::substr($product->name,0,30) }} @if(\Str::length($product->name) > 40)
                                                        ...
                                                    @endif  {{ $product->pivot->variant ? ' - '. $product->pivot->variant->option->valuable->title : '' }}</td>
                                                <td class="text-nowrap"><span
                                                        class="badge rounded-pill bg-label-secondary">{{ $product->barcode ?? trans('dashboard::common.unknown') }}</span>
                                                </td>
                                                <td>{{ $product->pivot->quantity }}</td>
                                                <td>{{ number_format($product->pivot->unit_price) }} </td>
                                                <td>@if($product->pivot->total_discount !=0 )
                                                        {{ number_format($unit_discount) }}
                                                    @else
                                                        0
                                                    @endif</td>
                                                <td>{{ number_format($product->pivot->total_discount) }}</td>
                                                <td> {{ number_format($product->pivot->subtotal) }} </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6" class="align-top px-4 py-5">
                                                <span>واحد پول تومان می‌باشد</span>
                                            </td>
                                            <td class="text-end px-4 py-5">
                                                <p class="mb-2">جمع جزء:</p>
                                                <p class="mb-2">کد تخفیف:</p>
                                                <p class="mb-2">حمل‌ونقل:</p>
                                                <p class="mb-0">جمع کل:</p>
                                            </td>
                                            {{--<td class="px-4 py-5">
                                                <p class="fw-semibold mb-2">154,000 تومان</p>
                                                <p class="fw-semibold mb-2">0 تومان</p>
                                                <p class="fw-semibold mb-2">50,000 تومان</p>
                                                <p class="fw-semibold mb-0">204,000 تومان</p>
                                            </td>--}}

                                            <td class="px-4 py-5">
                                                <p class="fw-semibold mb-2">{{ number_format($order->subtotal) }}</p>
                                                <p class="fw-semibold mb-2">{{ number_format($order->discount) }} </p>
                                                <p class="fw-semibold mb-2">{{ number_format($order->shipping) }}</p>
                                                <p class="fw-semibold mb-0">{{ number_format($order->total) }} </p>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="added-cards">
                                                @foreach($order->payments->sortByDesc('created_at') as $payment)
                                                    <div class="border p-3 rounded mb-1">
                                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                            <div class="card-information">
                                                                <table>
                                                                    <tr>
                                                                        <td colspan="3" class="align-top">
                                                                            <p class="mb-2">
                                                                                <span class="me-1 fw-semibold">تاریخ پرداخت</span>
                                                                                <span>{{ verta($payment->date)->format('j %B Y') }}</span>
                                                                            </p>
                                                                            <p class="mb-2">
                                                                                <span class="me-1 fw-semibold">روش پرداخت</span>
                                                                                <span>{{ $payment->payMode->display_name }}</span>
                                                                            </p>
                                                                            <p class="mb-2">
                                                                                <span class="me-1 fw-semibold">پیگیری بانک:</span>
                                                                                <span>{{ $payment->refrence_id }}</span>
                                                                            </p>
                                                                            <p class="mb-2">
                                                                                <span class="me-1 fw-semibold">نوع پرداخت:</span>
                                                                                <span>{{ $payment->payment_type == 'on-out' ? 'پرداختی به مشتری' : 'دریافتی از مشتری' }}</span>
                                                                            </p>
                                                                            <p class="mb-2">
                                                                                <span class="me-1 fw-semibold">یادداشت سیستمی:</span>
                                                                                <span>{{ $payment->notes }}</span>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('order_histories')
                            <div class="tab-pane fade" id="order-history" role="tabpanel">
                                <h5 class="card-title primary-font">تاریخچه وضعیت سفارش:</h5>
                                <div class="card-body">
                                    <ul class="timeline timeline-dashed mt-4">
                                        @foreach($order->histories as $history)
                                            <li class="timeline-item timeline-item-{{ $history->timeline_color }} mb-4">
                                    <span class="timeline-indicator timeline-indicator-{{ $history->timeline_color }}">
                                        <i class="bx bx-paper-plane"></i>
                                    </span>
                                                <div class="timeline-event">
                                                    <div class="timeline-header border-bottom mb-3">
                                                        <h6 class="mb-2">{{ $history->action }}</h6>
                                                        <small
                                                            class="text-muted">توسط: {{ $history->staff->full_name ?? 'سیستمی' }}</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between flex-wrap mb-2">
                                                        <div>
                                                            <span> آخرین وضعیت </span>
                                                            <i class="bx bx-right-arrow-alt scaleX-n1-rtl mx-2"></i>
                                                            <span>{{ \Modules\Order\Entities\Order::ONLINE_ORDER_STATUS[$history->last_status] }}</span>
                                                        </div>
                                                        <div>
                                                            <span>{{ verta($history->created_at)->format('j %B Y - H:i') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                        <li class="timeline-end-indicator">
                                            <i class="bx bx-check-circle"></i>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endcan
                        @can('order_cost')
                            <div class="tab-pane fade" id="commissions" role="tabpanel">
                                <div class="added-cards">
                                    @foreach($order->products as $product)
                                        <div class="border p-3 rounded mb-1">
                                            <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                <div class="card-information">
                                                    <h6 class="mb-1">{{ $product->name }} {{ $product->pivot->variant ? ' - '. $product->pivot->variant->option->valuable->title : '' }}</h6>
                                                    <span
                                                        class="d-flex"> خرید: {{ $product->pivot->variant ? number_format($product->pivot->variant->purchase_price) : number_format($product->detail->purchase_price) }} </span>
                                                    <span
                                                        class="d-flex"> فروش: {{ number_format($product->pivot->unit_price) }} </span>
                                                    <span
                                                        class="d-flex"> کمیسیون: {{ number_format($product->pivot->commission) }} </span>
                                                </div>
                                                <div class="d-flex flex-column text-start text-lg-end">
                                                    <div class="d-flex order-sm-0 order-1 mt-3">
                                                        @if($product->category != null AND $product->category->merchant_commission != 0)
                                                            <button class="btn btn-sm btn-label-primary me-3"> کمیسیون
                                                                متغییر
                                                            </button>
                                                            <button
                                                                class="btn btn-sm btn-label-secondary">{{ $product->category->merchant_commission }}
                                                                %
                                                            </button>
                                                        @else
                                                            <div class="d-flex">
                                                                <button class="btn btn-sm btn-label-primary me-2">
                                                                    کمیسیون
                                                                    ثابت
                                                                </button>
                                                                <button
                                                                    class="btn btn-sm btn-label-secondary">{{ company()->merchant_commission }}
                                                                    %
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="border p-3 rounded mb-1 bg-label-info">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                            <div class="card-information">
                                                <h6 class="mb-1">کل مبلغ کمیسیون</h6>
                                                <span>{{ number_format($order->total_commission) }}  </span><small>
                                                    تومان </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border p-3 rounded mb-1 bg-label-warning">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                            <div class="card-information">
                                                <h6 class="mb-1">هزینه محصولات</h6>
                                                <span>{{ number_format($order->subtotal - $order->total_commission - \Modules\Order\Entities\Order::gatewayFee($order) - $order->profit) }}  </span><small>
                                                    تومان </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border p-3 rounded mb-1 bg-label-danger">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                            <div class="card-information">
                                                <h6 class="mb-1">کارمزد درگاه بانک</h6>
                                                <span>{{ number_format(\Modules\Order\Entities\Order::gatewayFee($order)) }}  </span><small>
                                                    تومان </small>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="border p-3 rounded mb-1 bg-label-danger">
                                            <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                <div class="card-information">
                                                    <h6 class="mb-1">کد تخفیف</h6>
                                                    <span>{{ number_format($order->discount) }}  </span><small>
                                                        تومان </small>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="border p-3 rounded mb-1 bg-label-success">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                            <div class="card-information">
                                                <h6 class="mb-1">مجموع سود سفارش</h6>
                                                <span> {{ number_format($order->profit) }}  </span><small>
                                                    تومان </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
            <!-- /Invoice -->

            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                @can('change_order_status')
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="mb-3 bg-label-info p-2 rounded">
                                <h6 class="p-0 m-0">تغییر وضعیت سفارش</h6>
                            </div>

                            @if($order->order_status == "cancelled" or $order->order_status == "returned")
                                <div class="d-flex flex-wrap">
                                    <small class="text-danger">تغییر وضعیت برای سفارشات مرجوع شده و لغو شده ممکن نیست</small>
                                </div>
                            @else
                                <form action="{{ route('dashboard.online-order.change-order-status',$order->id) }}"
                                      id="changeOrderStatusForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-muted">با انتخاب بر روی هر کدام از
                                            وضعیت‌های
                                            زیر، وضعیت سفارش را تغییر دهید</label>
                                        <select name="status" class="form-control" id="status">
                                            <option disabled selected>یک وضعیت را انتخاب کنید</option>
                                            @foreach(\Modules\Order\Entities\Order::ONLINE_ORDER_STATUS as $key => $status)
                                                <option value="{{ $key }}"
                                                        @if($order->order_status == $key ) selected @endif>{{ $status }}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="d-flex justify-content-between lh-1-85 my-2">
                                        <label for="payment-terms" class="mb-0">ارسال پیامک تغییر وضعیت به مشتری</label>
                                        <label class="switch switch-primary me-0">
                                            <input type="checkbox" class="switch-input" name="sendSMS" id="payment-terms"
                                                   value="yes">
                                            <span class="switch-toggle-slider">
                                      <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                      </span>
                                      <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                      </span>
                                    </span>
                                            <span class="switch-label"></span>
                                        </label>
                                    </div>
                                    <div class="my-3 d-flex flex-wrap">
                                        <button type="submit" class="btn btn-primary me-3 btn-block submitChangeStatus">
                                            ذخیره
                                        </button>
                                    </div>

                                </form>
                            @endif
                        </div>
                    </div>
                @endcan

                @can('change_order_payment_status')
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="mb-3 bg-label-info p-2 rounded">
                                <h6 class="p-0 m-0">تغییر وضعیت پرداخت</h6>
                            </div>
                            @if($order->order_status == "cancelled" or $order->order_status == "returned")
                                <div class="d-flex flex-wrap">
                                    <small class="text-danger">تغییر وضعیت پرداخت برای سفارشات مرجوع شده و لغو شده ممکن
                                        نیست</small>
                                </div>
                            @else
                                <form action="{{ route('dashboard.online-order.change-payment-order-status',$order->id) }}"
                                      method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="status" class="form-label text-muted">با انتخاب بر روی هر کدام از
                                            وضعیت‌های
                                            زیر، وضعیت پرداخت سفارش را تغییر دهید</label>
                                        <select name="status" class="form-control" id="status">
                                            <option disabled selected>یک وضعیت را انتخاب کنید</option>
                                            @foreach(\Modules\Order\Entities\Order::ONLINE_PAYMENT_STATUS as $key => $status)
                                                <option value="{{ $key }}"
                                                        @if($order->payment_status == $key ) selected @endif>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="my-3 d-flex flex-wrap">
                                        <button type="submit" class="btn btn-primary me-3 btn-block">ذخیره</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                @endcan

                @can('insert_order_tracking_number')
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="mb-3 bg-label-info p-2 rounded">
                                <h6 class="p-0 m-0">شماره رهگیری</h6>
                            </div>
                            @if($order->order_status == "cancelled" or $order->order_status == "returned")
                                <div class="d-flex flex-wrap">
                                    <small class="text-danger">درج کد رهگیری مرسوله برای سفارشات مرجوع شده و لغو شده ممکن نیست</small>
                                </div>
                            @else
                                <form action="{{ route('dashboard.online-order.number-tracking-store',$order->id) }}"
                                      id="trackingNumberForm" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="shipping_method" class="form-label">نام شرکت حمل و نقل</label>
                                        <select name="shipping_method" class="form-control" id="shipping_method">
                                            <option disabled selected>نام شرکت حمل‌ونقل را انتخاب کنید</option>
                                            @foreach(\Modules\Order\Entities\Order::SHIPPING_PROVIDER as $key => $provider)
                                                <option value="{{ $key }}"
                                                        @if($key === $order->shipping_method) selected @endif >{{ $provider }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tracking_number" class="form-label text-muted">ذخیره کد رهگیری برای این
                                            سفارش. بعد از ذخیره‌سازی شماره پیگیری ما آن را به مشتری اعلام خواهیم کرد</label>
                                        <input name="tracking_number" class="form-control"
                                               value="{{ $order->tracking_number ? $order->tracking_number : '' }}"
                                               placeholder="کد رهگیری پست | شماره موبایل پیک " id="tracking_number"/>
                                    </div>

                                    <div class="my-3 d-flex flex-wrap">
                                        <button type="submit" class="btn btn-primary me-3 btn-block"
                                                id="submitTrackingNumber">
                                            ذخیره
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endcan
            </div>
            <!-- /Invoice Actions -->
        </div>
    </div>
    <!-- / Content -->
@endsection
@section('script')
    <script>
        $('.submitChangeStatus').on('click', function (event) {
            event.preventDefault();
            Swal.fire({
                title: "از تغییر وضعیت اطمینان دارید؟",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "آره اعمال کن",
                cancelButtonText: "نه دستم خورد",
                buttonsStyling: false,
            }).then(function (value) {
                if (!value.dismiss) {
                    $("#changeOrderStatusForm").submit();
                }
            });
        });
        $('#submitTrackingNumber').on('click', function (event) {
            event.preventDefault();
            Swal.fire({
                title: "از تغییر اطمینان دارید؟",
                text: "بعد از تایید برای مشتری پیامک ارسال خواهد شد!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "آره اعمال کن",
                cancelButtonText: "نه دستم خورد",
                buttonsStyling: false,
            }).then(function (value) {
                if (!value.dismiss) {
                    $("#trackingNumberForm").submit();
                }
            });
        });
    </script>
@endsection
