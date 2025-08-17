@extends('front::layouts.app')
@section('title',$offer->title)
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/front/css/plugins/jquery.classycountdown.min.css') }}">
@endsection
@section('mainContent')
    <!-- Page Content -->
    <main class="page-content">
        <div class="promotion-center--header">
            @if($offer->isOfferActive())
                <div class="promotion-center--title">
                    {{ $offer->title }}
                </div>
                <div class="d-flex justify-content-center">
                    <div id="countdown--offer-slider"></div>
                </div>
            @else
                <div class="promotion-center--title">
                   متاسفانه این کمپین فروش به پایان رسید
                </div>
            @endif

        </div>
        <div class="container translateY-25">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="listing-items row">
                        @foreach($offer->products as $product)
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 px-0">
                                <div class="product-box">
                                    <div class="product-box--thumbnail-container">
                            <span class="product-box--specialSell"
                                  @if(!$product->detail->isActivePromotion()) style="visibility: hidden" @endif></span>
                                        <img src="{{ $product->image_url }}" class="promotion-box--thumbnail"
                                             alt="product title">
                                        <a href="{{ route('front.product.show',[$product->id,$product->slug]) }}"
                                           class="product-box--link"></a>
                                    </div>
                                    <div class="product-box--detail">
                                        <h3 class="product-box--title">
                                            <a href="{{ route('front.product.show',[$product->id,$product->slug]) }}">
                                                {{ $product->name  }}
                                            </a>
                                        </h3>
                                        <div class="product-box--price-container">
                                            <div class="product-box--price-discount"
                                                 @if(!$product->detail->isActivePromotion()) style="visibility: hidden" @endif >
                                                @if($product->detail->isActivePromotion())
                                                    {{ round((($product->detail->sales_price - $product->detail->promotion_price)*100)/$product->detail->sales_price) }}%
                                                @endif
                                            </div>
                                            <div class="product-box--price">
                                    <span class="product-box--price-now">
                                                    <div class="product-box--price-value">
                                                        @if($product->detail->isActivePromotion())
                                                            {{ number_format($product->detail->promotion_price) }}
                                                        @else
                                                            {{ number_format($product->detail->sales_price) }}
                                                        @endif
                                                    </div>
                                                    <div class="product-box--price-currency">
                                                        <svg style="width: 18px; height: 18px;">
                                                            <use xlink:href="#toman"></use>
                                                        </svg>
                                                    </div>
                                                </span>
                                                @if($product->detail->isActivePromotion())
                                                    <span class="product-box--price-old">
                                            {{ number_format($product->detail->sales_price) }}
                                        </span>
                                                @endif
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

    </main>
    <!-- end Page Content -->
@endsection
@section('script')
    <script src="{{ asset('assets/front/js/plugins/jquery.classycountdown.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#countdown--offer-slider').ClassyCountdown({
                theme: "black",
                end: $.now() + {{ abs(verta($offer->end_date)->diffSeconds()) }},
                labels: false,
            });
        });
    </script>
@endsection
