@extends('front::layouts.app')
@section('title','جزئیات سفارش')

@section('mainContent')
    <!-- Page Content -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-5">
                @include('front::partials.profile-sidebar')
                <div class="col-lg-9 col-md-8">
                    <div class="section-title mb-2">
                        <span class="ml-2">جزئیات سفارش</span>
                        <span class="text-muted ml-2">|</span>
                        <span class="font-en ml-2">{{ $order->invoice_number }}</span>
                        <span class="text-muted ml-2">|</span>
                        <span class="text-sm text-secondary">{{ $order->date_time }}</span>
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <section class="shadow-around p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="checkout-section shadow-around p-2">
                                            <div class="checkout-section-content">
                                                <div class="cart-items bg-white">
                                                    <div class="mb-2">
                                                        <span class="text-muted">تحویل گیرنده:</span>
                                                        <span class="font-weight-bold">{{ $order->address->transferee }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-muted">شماره تماس :</span>
                                                        <span class="font-weight-bold">{{ $order->address->mobile }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-muted">ارسال به:</span>
                                                        <span class="font-weight-bold">{{ $order->address->full_address }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-muted">وضعیت :</span>
                                                        <span class="font-weight-bold text-success">{!! $order->online_order_status !!}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-muted">مبلغ :</span>
                                                        <span class="font-weight-bold">
                                                            {{ number_format($order->total) }}
                                                                <span class="text-sm text-secondary">تومان</span>
                                                            </span>
                                                    </div>
                                                    <hr>
                                                    @foreach($order->products as $product)
                                                        <div class="cart-item py-4 px-3">
                                                            <div class="item-thumbnail">
                                                                <a href="#">
                                                                    <img src="{{ $product->image_url }}"
                                                                         alt="item">
                                                                </a>
                                                            </div>
                                                            <div class="item-info flex-grow-1">
                                                                <div class="item-title">
                                                                    <h2>
                                                                        <a href="#">{{ $product->name }}</a>
                                                                    </h2>
                                                                </div>
                                                                <div class="item-detail">
                                                                    <ul>
                                                                        <li>
                                                                            @if(! is_null($product->pivot->variant_id))
                                                                                @if($product->pivot->variant->variant->type == "color")
                                                                                    <span> {{ $product->pivot->variant->option->valuable->title }}</span>

                                                                                    <span class="color"
                                                                                          style="background-color: {{ '#'.$product->pivot->variant->option->valuable->code }};"></span>

                                                                                @endif
                                                                                @if($product->pivot->variant->variant->type == "size")
                                                                                    سایز یا اندازه:
                                                                                    <span> {{ $product->pivot->variant->option->valuable->title }} </span>
                                                                                @endif
                                                                            @endif

                                                                        </li>
                                                                        <li>
                                                                            <i
                                                                                class="far fa-shield-check text-muted"></i>
                                                                            <span>گارانتی سلامت فیزیکی کالا</span>
                                                                        </li>

                                                                    </ul>
                                                                    <div class="item-quantity--item-price">
                                                                        <div class="item-price">
                                                                            <span class="text-muted"> قیمت :</span>
                                                                            @if(! is_null($product->pivot->variant))
                                                                                {{ number_format($product->pivot->variant->sales_price) }}
                                                                            @else
                                                                                {{ number_format($product->detail->sales_price) }}
                                                                            @endif
                                                                            <span
                                                                                class="text-sm mr-1">تومان</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
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
    <!-- end Page Content -->
@endsection

@section('script')

@endsection
