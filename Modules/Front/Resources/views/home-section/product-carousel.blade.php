<!-- product carousel -->
<section class="product-carousel in-box">
    <div class="section-title section-show-more">
        <span class="ml-2">{{ $item->title }}</span>
        <div class="mr-auto pt-1 pb-1">
            <a href="{{ route('front.category.list',[$item->rowable->slug,'ref'=>'showAll']) }}" target="_blank"
               class="text-muted text-black">
                مشاهده همه
                <i class="fas fa-chevron-left fa-2xs"></i>
            </a>

        </div>
    </div>

    <div class="swiper-container slider-lg pb-0">
        <div class="swiper-wrapper">
            @foreach($item->rowable->parentProduct($category->id) as $product)
                <div class="swiper-slide">
                    <div class="product-box">
                        <div class="product-box--thumbnail-container">
                            <span class="product-box--specialSell"
                                  @if(!$product->detail->isActivePromotion()) style="visibility: hidden" @endif></span>
                            <img src="{{ $product->image_url }}" class="product-box--thumbnail"
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
</section>
<!-- End Of product carousel -->
