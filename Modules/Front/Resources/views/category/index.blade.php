@extends('front::layouts.app')

@section('mainContent')
    <!-- Page Content -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-3 col-md-4 sticky-sidebar filter-options-sidebar">
                    <div class="d-md-none">
                        <div class="header-filter-options">
                            <span>جستجوی پیشرفته <i class="fad fa-sliders-h"></i></span>
                            <button class="btn-close-filter-sidebar"><i class="fal fa-times"></i></button>
                        </div>
                    </div>
                    <!-- start sidebar widget -->

                    <form action="{{ route('front.category.list',$category->slug) }}" method="get">
                        <div class="sidebar-widget">
                            @if($category->variant != null AND $category->variant->type == \Modules\Product\Entities\VariantValue::COLOR)
                                    @php $colors = request()->query('colors'); @endphp
                                    <div class="widget widget-filter-options shadow-around">
                                        <div class="widget-title">فیلتر براساس {{ $category->variant->title }}</div>
                                        <div class="widget-content pl-3">
                                            @foreach(collect($category->variant->values()->whereHas('ProductVariant')->get())
                                                            ->unique('valuable_id')->values()->all() as $key => $value)
                                                <label class="container-checkbox">
                                            <span class="d-flex justify-content-between align-items-center">
                                                <span>{{ $value->valuable->title }}</span>
                                                <span class="color-option"
                                                      style="background-color: {{ '#' .$value->valuable->code }};"></span>
                                            </span>
                                                    <input type="checkbox"
                                                           @if(!empty($colors) && in_array($value->valuable->id,$colors))
                                                               checked
                                                           @endif
                                                           onchange="this.form.submit()" name="colors[{{ $key }}]"
                                                           value="{{ $value->valuable->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                            @endif

                            @if($category->variant != null AND $category->variant->type == \Modules\Product\Entities\VariantValue::SIZE)
                                @php
                                    $sizes = request()->query('sizes');
                                @endphp
                                <div class="widget widget-filter-options shadow-around">
                                    <div class="widget-title">فیلتر براساس {{ $category->variant->title }}</div>
                                    <div class="widget-content pl-3">
                                        @foreach(collect($category->variant->values()->whereHas('ProductVariant')->get())
                                                        ->unique('valuable_id')->values()->all() as $key => $value)
                                            <label class="container-checkbox">
                                                <span class="d-flex justify-content-between align-items-center">
                                                    <span>{{ $value->valuable->title }}</span>
                                                </span>
                                                <input type="checkbox"
                                                       @if(!empty($sizes) && in_array($value->valuable->id,$sizes))
                                                           checked
                                                       @endif
                                                       name="sizes[{{ $key }}]"
                                                       onchange="this.form.submit()"
                                                       value="{{ $value->valuable->id }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($category->group()->has('attributes')->exists())
                                    @foreach($category->group->attributes()->where('is_filterable',true)->get() as $attribute)
                                        @php
                                            $attributes = request()->query('attributes');
                                        @endphp
                                        <div class="widget widget-filter-options shadow-around">
                                            <div class="widget-title">{{ $attribute->name }}</div>
                                            <div class="widget-content">
                                                @foreach($attribute->values as $index => $value)
                                                    <label class="container-checkbox">
                                                        {{ $value->value }}
                                                        <input type="checkbox"
                                                               name="attributes[{{ $attribute->id }}][{{ $index }}]"
                                                               @if(!empty($attributes[$attribute->id]) && in_array($value->id,$attributes[$attribute->id]))
                                                                   checked
                                                               @endif
                                                               onchange="this.form.submit()"
                                                               value="{{ $value->id }}">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                            @endif

                            <div class="widget shadow-around d-none">
                                <div class="widget-content">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                        <label class="custom-control-label" for="customSwitch1">فقط کالاهای موجود در
                                            انبار</label>
                                    </div>
                                </div>
                            </div>
                            <div class="widget shadow-around d-none">
                                <div class="widget-title">محدوده قیمت</div>
                                <div class="widget-content">
                                    <form action="#" class="filter-price">
                                        <div class="mt-2 mb-2">
                                            <div class="filter-slider">
                                                <div id="slider-non-linear-step" class="price-slider"></div>
                                            </div>
                                            <ul class="filter-range">
                                                <li data-label="از" data-currency=" تومان">
                                                    <input type="text" data-value="0" value="0" name="price[min]"
                                                           data-range="0"
                                                           class="js-slider-range-from disabled example-val"
                                                           id="skip-value-lower" disabled="disabled">
                                                </li>
                                                <li data-label="تا" data-currency="تومان">
                                                    <input type="text" data-value="17700000" value="17700000"
                                                           name="price[max]" data-range="17700000"
                                                           class="js-slider-range-to disabled example-val"
                                                           id="skip-value-upper" disabled="disabled">
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <button class="btn btn-primary d-inline-flex align-items-center">
                                                <i class="fad fa-funnel-dollar ml-2"></i>
                                                اعمال محدوده قیمت
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="d-md-none">
                                <button class="btn-filter-product-submit">جستجوی پیشرفته</button>
                            </div>
                        </div>
                    </form>
                    <!-- end sidebar widget -->
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="d-md-none">
                        <button class="btn-filter-sidebar">
                            جستجوی پیشرفته <i class="fad fa-sliders-h"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <!-- breadcrumb -->
                            <div class="breadcrumb mb-2 pt-2">
                                <nav>
                                    <a href="{{ route('front.home') }}">{{ get_short_name() }}</a>
                                    @if($categoriesBreadcrumb)
                                        @include('front::product.parent_categories', ['categoriesBreadcrumb' => $categoriesBreadcrumb->parentsCategories])
                                        <a href="{{ route('front.category.list',$categoriesBreadcrumb->slug) }}">{{ $categoriesBreadcrumb->title_fa }}</a>
                                    @endif
                                </nav>
                            </div>

                            <!-- end breadcrumb -->
                        </div>
                    </div>
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
                                                 alt="{{ $product->name }}"
                                                 loading="lazy">
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
                    <div class="row rounded border my-2 mx-0">
                        <div class="col-12 p-4">
                            {!! $category->description !!}
                        </div>
                    </div>
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
