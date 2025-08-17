@if($item->rowable->isOfferActive())
    <section class="product-carousel amazing-section mb-5 border-0 p-1"
             style="background: rgb(230, 18, 61);">
        <div class="row align-items-center">
            <div class="col-xl-2 col-lg-3 amazing-img-column">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <h5 class="text-white text-center py-2">{{ $item->title }}</h5>
                    <a href="{{ route('offer.show',$item->rowable->slug) }}" target="_blank"
                       class="btn btn-sm text-white border text-center my-1 d-none d-md-block">مشاهده همه</a>
                </div>
            </div>
            <div class="col-xl-10 col-lg-9">
                <div class="swiper-container slider-amazing pt-1 pb-1">
                    <div class="swiper-wrapper custom-wrapper">
                        @foreach($item->rowable->products as $product)
                            <div class="swiper-slide">
                                <div class="product-box">
                                    <div class="product-box--thumbnail-container">
                            <span class="product-box--specialSell"
                                  @if(!$product->detail->isActivePromotion()) style="visibility: hidden" @endif></span>
                                        <img src="{{ $product->image_url }}" class="promotion-box--thumbnail"
                                                 alt="{{ $product->name }}" loading="lazy">
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
                    <!-- Add Arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </section>
@endif
