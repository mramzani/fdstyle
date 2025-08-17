<div class="user-item cart-list">
    <a href="{{ route('front.cart-product.all') }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="1.5" stroke="currentColor" width="27">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
        </svg>
        <span class="bag-items-number">{{ Cart::instance('shopping')->itemCount() }}</span>
    </a>
    @if(Cart::instance('shopping')->itemCount() >= 1)
        <ul>
            <li class="cart-items">
                <ul class="do-nice-scroll">
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
                                               <span style="background-color:  {{ '#'.$item->variantSelected->option->valuable->code }};"></span>
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
            <li class="cart-footer d-flex align-items-center justify-content-between">
            <span class="d-flex flex-column">
                <span>مبلغ کل:</span>
                <span class="total">{{ number_format(Cost::getTotalCosts()) }} تومان</span>
            </span>
                <span class="d-block text-center px-2">
                <a href="{{ route('front.cart-product.all') }}" class="btn-cart">
                ثبت سفارش
                </a>
            </span>
            </li>
        </ul>
    @endif

</div>
