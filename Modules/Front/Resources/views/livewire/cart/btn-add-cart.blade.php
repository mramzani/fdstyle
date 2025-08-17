<div class="row">
    @php
        $product = (object) $product;
    @endphp
    <div class="col-sm-7 col-12">
        @if($product->category != null)
            <div class="d-block my-2">
                <span class="font-weight-bold">دسته‌بندی:</span>
                <a href="{{ route('front.category.list',$product->category['slug']) }}"
                   class="link--with-border-bottom">{{ $product->category['title_fa'] }}
                    | {{ $product->category['title_en'] }}</a>
            </div>
        @endif
        @if($product->brand != null)
            <div class="d-block my-2">
                <span class="font-weight-bold">برند:</span>
                <a href="{{ route('front.brand.list',$product->brand['slug']) }}"
                   class="link--with-border-bottom">{{ $product->brand['title_fa'] }}
                    | {{ $product->brand['title_en'] }}</a>
            </div>
        @endif
        @if($product->variants != null && $product->default_variant != null)
            {{--  Product Variant--}}
            <div class="product-variant-container p-1 my-2">
                                        <span
                                            class="d-block mb-3">{{ $product->variants['title'] }} مورد نظر خود را انتخاب کنید</span>
                @if($product->variants['type'] == "color")
                    <div class="d-flex align-items-center flex-wrap">
                        @foreach($product->variants['list'] as $productVariant)
                            @php
                                $productVariant = (object) $productVariant;
                            @endphp
                            <div class="col ml-1">
                                <div class="text-center">

                                    <input type="radio" class="custom-radio-circle-input"
                                           wire:change="changeVariant({{ $productVariant->id }},'{{ $productVariant->title }}')"
                                           wire:model="variantSelected"
                                           value="{{ $productVariant->id }}"
                                           id="productColor-{{ $productVariant->id }}">
                                    <label
                                        for="productColor-{{ $productVariant->id }}"
                                        class="d-inline-block custom-radio-circle-label">
                                        <span class="radio-color"
                                              style="background-color: {{ '#' . $productVariant->code}}">
                                        
                                        </span>
                                    </label>
                                    <p class="font-small text-center">{{ $productVariant->title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @elseif($product->variants['type'] == "size")
                    <div class="d-flex align-items-center flex-wrap">
                        @foreach($product->variants['list'] as $productVariant)
                            @php
                                $productVariant = (object) $productVariant;
                            @endphp
                            <div class="col-2 ml-0">
                                <div class="text-center">
                                    <input type="radio" class="custom-radio-circle-input"
                                           wire:change="changeVariant({{ $productVariant->id }},{{ $productVariant->title }})"
                                           wire:model="variantSelected"
                                           value="{{ $productVariant->id }}"
                                           id="productSize-{{ $productVariant->id }}">
                                    <label for="productSize-{{ $productVariant->id }}"
                                           class="d-inline-block custom-radio-circle-label">
                                        <span class="radio-size">
                                            @if($variantSelected !== null and $productVariant->id == $variantSelected)
                                                <i class="fal fa-check"></i>
                                            @else
                                                <p class="font-small text-center">{{ $productVariant->title }}</p>
                                            @endif
                                            
                                        </span>
                                        
                                    </label>
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
        {{-- End Product Variant--}}

        {{--product params special--}}
        @if(count($product->attributes) > 0)
            <div class="product-params-special" style="margin-top: 10px">
                <ul data-title="ویژگی‌های محصول">
                    @foreach(collect($product->attributes)->take(4)->all() as $att)
                        <li>
                            <span>{{$att['name']}}:</span>
                            <span>{{$att['value']}}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{--end product params special--}}

        <div class="alert alert-primary bg-transparent">
            <div class="alert-body">
                <p class="d-flex align-items-center">
                    <i class="fad fa-history ml-2"></i>
                    @if(verta()->hour < 12)
                        درصورت ثبت سفارش قبل از ساعت ۱۲، امروز ارسال خواهد شد.
                    @else
                        این محصول حداکثر تا {{ $product->preparation_time + 2 }} روز تحویل
                        داده می شود.
                    @endif
                </p>
            </div>
        </div>

    </div>
    <div class="col-sm-5 col-12">

        <div class="product-seller-info box-info-product border rounded-md">
            @if($product->default_variant != null)
                <div class="seller-info-changeable">
                    <div
                        class="d-flex align-items-center justify-content-between flex- font-weight-bold">
                        <span class="label text-muted">اطلاعات جانبی</span>
                        <a href="#" class="anchor-link link border-bottom-0 fs-7 fa-num d-none">
                            فروشندگان دیگر
                        </a>
                    </div>
                    <div class="border-1 border-bottom d-flex align-items-center py-2">
                        <div class="text-center" style="width: 60px">
                            <img src="{{ asset('assets/front/images/icons/seller.png') }}"
                                 width="32"
                                 onerror="this.src='{{ asset('assets/front/images/icons/seller.png') }}'"
                                 class="rounded-5">
                        </div>
                        <div class="position-relative flex-grow-1 ml-1 ">
                            <div class="font-weight-bold mb-1"
                                 style="font-size: 16px;">{{ $product->default_variant['seller']['title'] }}</div>
                        </div>
                    </div>
                    <div class="border-1 border-bottom d-flex align-items-center py-2">
                        <div class="text-center" style="width: 60px">
                            <img src="{{ asset('assets/front/images/icons/guarantee.png') }}"
                                 width="32"
                                 onerror="this.src='{{ asset('assets/front/images/icons/guarantee.png') }}'"
                                 class="rounded-5">
                        </div>
                        <div class="position-relative flex-grow-1 ml-1 ">
                            <div class="font-weight-bold mb-1 text-danger" style="font-size: 16px;">
                                {{ $product->default_variant['warranty']['title_fa'] }}

                            </div>
                            <div class="d-flex">
                                <span class="text-sm">
                                    {{ $product->default_variant['warranty']['description'] }}
                                     <a href="{{ $product->default_variant['warranty']['link'] }}" class="text-sm text-muted"> توضیحات </a>
                                </span>

                            </div>
                        </div>
                    </div>
                    <div class="border-1 border-bottom d-flex align-items-center py-2">
                        <div class="text-center" style="width: 60px">
                            <img src="{{ asset('assets/front/images/icons/package.png') }}"
                                 width="32"
                                 onerror="this.src='{{ asset('assets/front/images/icons/package.png') }}'"
                                 class="rounded-5">
                        </div>
                        <div class="position-relative flex-grow-1 ml-1 ">
                            <div class="font-weight-bold mb-1" style="font-size: 16px;">آماده
                                ارسال
                            </div>
                            <div class="">
                                <span style="color: #81858b">موجود در انبار | مدت زمان تحویل: {{ $product->preparation_time + 2 }} روز </span>
                            </div>
                        </div>
                    </div>
                    @if($product->variants != null)
                        <div class="border-1 border-bottom d-flex align-items-center py-2">
                            <div class="text-center" style="width: 60px">
                                <img src="{{ asset('assets/front/images/icons/select.png') }}"
                                     width="32"
                                     onerror="this.src='{{ asset('assets/front/images/icons/select.png') }}'"
                                     class="rounded-5">
                            </div>

                            <div class="position-relative flex-grow-1 ml-1 ">
                                <div class="font-weight-bold mb-1" style="font-size: 16px;">
                                    {{ $product->variants['title'] }} انتخابی:
                                    <span
                                        id="variant-title">{{ $variantTitle }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="d-flex align-items-center justify-content-end mt-3">
                        <div class="product-price">
                            @if($product->default_variant['isPromotion'])
                                <div class="product-price-info">
                                    <div class="product-price-off">
                                        %{{ round((($price - $product->default_variant['promotion_price'])*100)/$product->default_variant['promotion_price']) }}
                                        <span>تخفیف</span>
                                    </div>
                                    <div class="product-price-prev">
                                        {{ number_format($price) }}
                                    </div>
                                </div>
                            @endif

                            <div class="product-price-real">
                                @if($product->default_variant['isPromotion'])
                                    <div
                                        class="product-price-raw">{{ number_format($product->default_variant['promotion_price']) }}</div>
                                @else
                                    <div class="product-price-raw">{{ number_format($price) }}</div>
                                @endif

                                <svg style="width: 18px; height: 18px;">
                                    <use xlink:href="#toman"></use>
                                </svg>
                            </div>
                        </div>
                    </div>
                    {{--@if($product->default_variant['stock'] <= 10)
                        <div class="my-2">
                            <div class="text-sm text-center text-danger font-weight-bold "
                                 style="width: 100%">
                                            <span>تنها <span
                                                    class="mx-1">{{ $product->default_variant['stock'] }}</span>
                                                عدد در انبار باقیست - پیش از
                                                اتمام بخرید
                                            </span>
                            </div>
                        </div>
                    @endif--}}
                    {{-- {{ dd(Cart::instance('shopping')->has($variant)) }}--}}
                    @if(Cart::instance('shopping')->has($variant))
                        <div class="d-flex mb-3">
                            <div class="num-block d-flex justify-content-between px-2 shadow shadow-sm">
                                <div class="num-in justify-content-around">
                                    <span class="plus" wire:click="up">
                                        <i class="far fa-plus"></i>
                                    </span>
                                    <input type="text" class="in-num" wire:model.lazy="qty" readonly>
                                    <span class="minus dis" wire:click="down">
                                        @if($qty == 1)
                                            <i class="far fa-trash-alt"></i>
                                        @else
                                            <i class="far fa-minus"></i>
                                        @endif

                                    </span>
                                </div>
                            </div>
                            <div class="mr-2 mr-lg-4 d-lg-block shrink-0">
                                <p class="text-bold-400">در سبد شما</p>
                                <div class="d-flex text-sm">مشاهده
                                    <a href="{{ route('front.cart-product.all') }}">
                                        <p class="mr-1">سبد خرید</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else

                        <div class="d-flex align-items-center">
                            <button wire:click="addCart"
                                    id="btnAddCart"
                                    class="btn btn-danger btn-block">
                                افزودن به سبد خرید
                            </button>
                        </div>
                        
                    @endif
                </div>
            @else
                <div class="p-1 text-lg-center">
                    <div
                        class="mb-2 text-center d-flex align-items-center justify-content-center position-relative buyBoxNotAvailable">
                        <p class="px-4" style="z-index: 1;background-color: #f5f5f5">ناموجود</p>
                    </div>
                    <p class="color-700 text-center text-body-1">این کالا فعلا موجود نیست</p>
                </div>
            @endif
        </div>
    </div>
</div>
