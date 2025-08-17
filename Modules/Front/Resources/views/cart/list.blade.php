@extends('front::layouts.app')
@section('title','سبد خرید - ' . company()->site_title)
@section('mainContent')
    <!-- Page Content -->
    <main class="page-content">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    @include('front::partials.alert')
                </div>
            </div>

            <div class="row mb-4">
                @if(Cart::instance('shopping')->itemCount() == 0)
                    <div class="col-xl-9 col-lg-8 col-md-8 mb-md-0 mb-3">
                        <div class="checkout-section shadow-around">
                            <div class="align-items-center justify-content-center">
                                <div class="text-h4 text-center mt-4 ">سبد خرید شما خالی است!</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4">
                        @if(!auth('customer')->check())
                            <div class="shadow-around">
                                <div class="d-flex px-3 my-2 py-2">
                                    <a href="{{ route('front.user.login') }}" class="btn btn-outline-danger btn-block py-2">ورود به حساب کاربری</a>
                                </div>
                                <div class="my-2 px-3">
                                    <p class="text-muted">برای مشاهده محصولاتی که پیش‌تر به سبد خرید خود اضافه کرده‌اید وارد شوید.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                @else
                    <div class="col-xl-9 col-lg-8 col-md-8 mb-md-0 mb-3">
                        <div class="checkout-section shadow-around py-2">
                            <div class="checkout-section-content">
                                <div class="cart-items p-3">
                                    @foreach(Cart::instance('shopping')->all() as $item)
                                        <div class="cart-item py-4 px-3 d-none">
                                            <div class="d-flex flex-column">
                                                <div class="item-thumbnail mb-2">
                                                    <a href="{{ route('front.product.show',[$item->id,$item->slug]) }}">
                                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                                                    </a>
                                                </div>
                                                <div class="item-quantity">
                                                    @livewire('front::cart.plus-minus-btn', ['product' => $item])
                                                </div>
                                            </div>
                                            <div class="item-info flex-grow-1">
                                                <div class="item-title">
                                                    <h2>
                                                        <a href="{{ route('front.product.show',[$item->id,$item->slug]) }}">{{ $item->name }}</a>
                                                    </h2>
                                                </div>
                                                <div class="item-detail">
                                                    <ul>
                                                        <li>
                                                            @if(! is_null($item->variantSelected))
                                                                @if($item->variantSelected->variant->type == "color")
                                                                    <span> {{ $item->variantSelected->option->valuable->title }}</span>

                                                                    <span class="color"
                                                                          style="background-color: {{ '#'.$item->variantSelected->option->valuable->code }};"></span>

                                                                @endif
                                                                @if($item->variantSelected->variant->type == "size")
                                                                    سایز یا اندازه:
                                                                    <span> {{ $item->variantSelected->option->valuable->title }} </span>
                                                                @endif
                                                            @endif
                                                        </li>
                                                        <li>
                                                            <i class="far fa-clipboard-check text-primary"></i>
                                                            <span>موجود در انبار</span>
                                                        </li>
                                                    </ul>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-start ml-2">
                                                                <span
                                                                    class="text-h4">
                                                                    @if(! is_null($item->variantSelected))
                                                                        {{ number_format($item->variantSelected->sales_price) }}
                                                                    @else
                                                                        {{ number_format($item->detail->sales_price) }}
                                                                    @endif
                                                                    </span>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="d-flex mr-1">
                                                                            <svg style="width: 18px; height: 18px;">
                                                                                <use xlink:href="#toman"></use>
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cart-item py-4 px-3">
                                            <div class="item-thumbnail">
                                                <a href="{{ route('front.product.show',[$item->id,$item->slug]) }}">
                                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                                                </a>
                                            </div>
                                            <div class="item-info flex-grow-1">
                                                <div class="item-title">
                                                    <h2>
                                                        <a href="{{ route('front.product.show',[$item->id,$item->slug]) }}">{{ $item->name }}</a>
                                                    </h2>
                                                </div>
                                                <div class="item-detail">
                                                    <ul>
                                                        <li>
                                                            @if(! is_null($item->variantSelected))
                                                                @if($item->variantSelected->variant->type == "color")
                                                                    <span> {{ $item->variantSelected->option->valuable->title }}</span>

                                                                    <span class="color"
                                                                          style="background-color: {{ '#'.$item->variantSelected->option->valuable->code }};"></span>

                                                                @endif
                                                                @if($item->variantSelected->variant->type == "size")
                                                                    سایز یا اندازه:
                                                                    <span> {{ $item->variantSelected->option->valuable->title }} </span>
                                                                @endif
                                                            @endif
                                                        </li>
                                                        <li>
                                                            <i class="far fa-shield-check text-muted"></i>
                                                            <span>گارانتی سلامت فیزیکی کالا</span>
                                                        </li>
                                                        <li>
                                                            <i class="far fa-store-alt text-muted"></i>
                                                            <span>{{ get_short_name()  }}</span>
                                                        </li>
                                                        <li>
                                                            <i class="far fa-clipboard-check text-primary"></i>
                                                            <span>موجود در انبار</span>
                                                        </li>
                                                    </ul>
                                                    <div class="item-quantity--item-price">
                                                        @livewire('front::cart.plus-minus-btn', ['product' => $item])
                                                        <div class="item-price">
                                                            @if(!is_null($item->variantSelected) AND !$item->detail->isActivePromotion())
                                                                {{ number_format($item->variantSelected->sales_price) }}
                                                            @else
                                                                @if($item->detail->isActivePromotion())
                                                                    <div class="text-muted text-left text-sm">
                                                                        <del>{{ number_format($item->detail->sales_price) }}</del>
                                                                    </div>
                                                                    <span class="text-danger">{{ number_format($item->detail->promotion_price) }}</span>
                                                                @else
                                                                    {{ number_format($item->detail->sales_price) }}
                                                                @endif
                                                            @endif
                                                            <span class="text-sm mr-1">
                                                                <svg style="width: 18px; height: 18px;">
                                                                    <use xlink:href="#toman"></use>
                                                                </svg>
                                                            </span>
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
                    <div class="col-xl-3 col-lg-4 col-md-4">
                        <div class="shadow-around pt-3">
                            @livewire('front::cart.summery-box')
                            <div class="d-flex px-3 py-2">
                                <a href="{{ route('front.checkout.shipping') }}" class="btn btn-primary btn-block py-2">ادامه فرایند خرید</a>
                            </div>
                        </div>
                        <div class="my-2 px-2">
                            <p class="text-muted">هزینه این سفارش هنوز پرداخت نشده‌ و در صورت اتمام موجودی، کالاها از سبد حذف می‌شوند</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
    <!-- end Page Content -->
@endsection
@section('script')
    <script type="text/javascript">
        window.addEventListener('show-toast',event => {
            event.preventDefault();
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: event.detail.type,
                title: event.detail.message
            });
            setTimeout(function () {
                location.reload();
            },3000)
        });
        $(document).ready(function () {
            RINO.Quantity();
            RINO.SweetAlert();
        });
    </script>
@endsection
