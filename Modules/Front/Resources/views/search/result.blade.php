@extends('front::layouts.app')
@section('mainContent')
    <!-- Page Content -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-5">

                <div class="col-lg-12 col-md-12">
                    <!-- start product list -->
                    <div class="listing-items row">
                        @if(count($products) > 0)
                            @foreach($products as $product)

                                <div class="col-lg-3 col-md-4 col-sm-6 px-0">
                                    <div class="product-box">
                                        <div class="product-box--thumbnail-container">
                                                <span class="product-box--specialSell"
                                                      @if(!$product->hasStockWithVariant() OR !$product->detail->isActivePromotion()) style="visibility: hidden" @endif></span>
                                            <img src="{{ $product->image_url }}" class="product-box--thumbnail"
                                                 alt="product title">
                                            <a href="{{ route('front.product.show',[$product->id,$product->slug]) }}"
                                               class="product-box--link"></a>
                                        </div>
                                        <div class="product-box--detail">
                                            <h3 class="product-box--title">
                                                <a href="{{ route('front.product.show',[$product->id,$product->slug]) }}">
                                                    {{ \Str::substr($product->name,0,40)  }}{{ \Str::length($product->name) > 40 ? '...' : '' }}
                                                </a>
                                            </h3>
                                            <div class="product-box--price-container">
                                                @if($product->hasStockWithVariant())
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
                                                @else
                                                    <span class="product-box--price-old">
                                                            ناموجود
                                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-12 px-0">
                                {{ $products->withQueryString()->links('front::partials.pagination') }}
                            </div>
                        @else
                            <div class="p-4">
                                <p>نتیجه‌ای یافت نشد.</p>
                            </div>
                        @endif

                    </div>
                    <!-- end product list -->
                </div>
            </div>
        </div>
    </main>
    <!-- end Page Content -->
@endsection
@section('script')
    <script>
        RINO.FilterOptionsSidebar();
    </script>
@endsection
