<div>
    <div class="cart-side">
        <a href="{{ route('front.cart-product.all') }}" class="btn-toggle-cart-side ml-0">
            <i class="far fa-shopping-basket"></i>
            <span class="bag-items-number">{{ Cart::instance('shopping')->itemCount() }}</span>
        </a>
        @if(Cart::instance('shopping')->itemCount() >= 1)
            <div class="cart-side-content">
                <ul>
                    <li class="cart-items">
                        <ul>
                            @foreach(Cart::instance('shopping')->all() as $item)
                                <li class="cart-item">
                                        <span class="d-flex align-items-center mb-2">
                                            <a href="{{ route('front.product.show',[$item->id,$item->slug]) }}">
                                                <img src="{{ $item->image_url }}" alt="">
                                            </a>
                                            <span>
                                                <a href="{{ route('front.product.show',[$item->id,$item->slug]) }}">
                                                    <span class="title-item">
                                                        {{ $item->name }}
                                                    </span>
                                                </a>
                                                @if(! is_null($item->variantSelected))
                                                    @if($item->variantSelected->variant->type == "color")
                                                        <span class="color d-flex align-items-center">
                                               رنگ:
                                               <span
                                                   style="background-color:  {{ '#'.$item->variantSelected->option->valuable->code }};"></span>
                                            </span>
                                                    @endif
                                                    @if($item->variantSelected->variant->type == "size")
                                                        <span class="d-flex align-items-center">
                                               سایز :
                                               <span>{{ $item->variantSelected->option->valuable->title }}</span>
                                           </span>
                                                    @endif
                                                @endif
                                            </span>
                                        </span>

                                    @if($item->detail->isActivePromotion())
                                        <span class="price">{{ number_format($item->detail->promotion_price) }}
                                        <svg style="width: 18px; height: 18px;">
                                            <use xlink:href="#toman"></use>
                                        </svg>
                                    </span>
                                    @else
                                        <span class="price">{{ number_format($item->detail->sales_price) }}
                                        <svg style="width: 18px; height: 18px;">
                                            <use xlink:href="#toman"></use>
                                        </svg>
                                    </span>
                                    @endif

                                    -
                                    <span class="price"> {{ $item->quantity }}  عدد </span>
                                    <button class="remove-item">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </li>
                            @endforeach

                        </ul>
                    </li>
                    <li class="cart-footer">
                                <span class="d-block text-center mb-3">
                                            مبلغ کل:
                                    <span class="total">{{ number_format(Cost::getTotalCosts()) }} تومان</span>
                                </span>
                        <span class="d-block text-center px-2">
                                    <a href="{{ route('front.cart-product.all') }}" class="btn-cart">
                                                سبد خرید
                                        <i class="mi mi-ShoppingCart"></i>
                                    </a>
                                </span>
                    </li>
                </ul>
            </div>
        @endif()
        <div class="overlay-cart-side"></div>
    </div>

</div>
