@extends('front::layouts.app')

@section('mainContent')
     
    <!-- Page Content -->
    <main class="page-content">
        <div class="container">
            <div class="row mb-1">
                <div class="col-12">
                    <!-- breadcrumb -->
                    <div class="breadcrumb mb-1">
                        <nav>
                            <a href="{{ route('front.home') }}">{{ get_short_name() }}</a>
                            @if($categoriesBreadcrumb)
                                @include('front::product.parent_categories', ['categoriesBreadcrumb' => $categoriesBreadcrumb->parentsCategories])
                                <a href="{{ route('front.category.list',$categoriesBreadcrumb->slug) }}">{{ $categoriesBreadcrumb->title_fa }}</a>
                            @endif
                        </nav>
                    </div>
                    <!-- end breadcrumb -->
                    @include('front::partials.alert')
                </div>
            </div>
            <div class="product-detail shadow-around mb-5 py-5">
                <div class="row mb-3 mx-0">
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-md-0 mb-3">
                        <div class="product-gallery">
                            <div class="swiper-container gallery-slider pb-md-0 pb-3">
                                <div class="swiper-wrapper">
                                    @if(count($data->images['list']) > 0)
                                        @foreach($data->images['list'] as $index => $image)
                                            <div class="swiper-slide">
                                                <img src="{{ $image['url'] }}" alt="{{ $data->title_fa }}"
                                                     data-large="{{ $image['url'] }}" class="zoom-image">
                                            </div>
                                        @endforeach
                                    @else
                                        <img src="{{ $data->images['main']['url'] }}" alt="{{ $data->title_fa }}"
                                             data-large="{{ $data->images['main']['url'] }}" class="zoom-image">
                                    @endif
                                </div>
                                <!-- Add Pagination -->
                                <div class="swiper-pagination d-md-none"></div>
                            </div>
                            @if(count($data->images['list']) > 0)
                                <div class="swiper-container gallery-slider-thumbs d-md-block d-none">
                                    <div class="swiper-wrapper">
                                        @foreach($data->images['list'] as $image)
                                            <div class="swiper-slide">
                                                <img src="{{ $image['url'] }}" alt="{{ $data->title_fa }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Add Arrows -->
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-6 mx-lg-0 mx-auto">
                        <div class="d-flex align-items-center w-100 px-2">
                            <div class="product-title mb-3">
                                <h1>
                                    {{ $data->title_fa }}
                                </h1>
                            </div>
                        </div>
                        @livewire('front::cart.btn-add-cart', ['product' => $product])
                    </div>
                </div>
            </div>
            <!-- product-tab-content -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="product-tab-content">
                        <ul class="nav nav-tabs" id="product-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="productDescription-tab" data-toggle="tab"
                                   href="#productDescription" role="tab" aria-controls="productDescription"
                                   aria-selected="true">توضیحات</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="productParams-tab" data-toggle="tab" href="#productParams"
                                   role="tab" aria-controls="productParams" aria-selected="false">مشخصات</a>
                            </li>
                            @if($product->brand AND $product->brand->sizeGuide)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="sizeGuide-tab" data-toggle="tab" href="#sizeGuide"
                                       role="tab" aria-controls="sizeGuide" aria-selected="false">راهنمای سایز</a>
                                </li>
                            @endif


                        </ul>
                        <div class="tab-content" id="product-tab">
                            <div class="tab-pane fade show active" id="productDescription" role="tabpanel"
                                 aria-labelledby="productDescription-tab">
                                <div class="product-desc">
                                    <div class="accordion accordion-product" id="accordionDescription">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                @if($product->description != null)
                                                    {!! $product->description !!}
                                                @else
                                                    <div class="alert alert-info">برای این کالا توضیحی درج نشده است.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="productParams" role="tabpanel"
                                 aria-labelledby="productParams-tab">
                                <div class="product-params">
                                    @if(count($data->attributes) > 0)
                                        <section>
                                            <h3 class="params-title">مشخصات کلی</h3>
                                            <ul class="params-list">
                                                @foreach($data->attributes as $attribute)
                                                    <li>
                                                        <div class="params-list-key">
                                                            <span>{{ $attribute['name'] }}</span>
                                                        </div>
                                                        <div class="params-list-value">
                                                        <span>
                                                           {{ $attribute['value'] }}
                                                        </span>
                                                        </div>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </section>
                                    @else
                                        <div class="alert alert-info">مشخصاتی برای این محصول درج نشده است.</div>
                                    @endif
                                </div>
                            </div>
                            @if($product->brand AND $product->brand->sizeGuide)
                            <div class="tab-pane fade" id="sizeGuide" role="tabpanel" aria-labelledby="sizeGuide-tab">
                                <div class="product-size-guide">
                                    {!! $product->brand->sizeGuide->description !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- end product-tab-content -->

        </div>
        <!-- Modal -->
            <div class="modal fade" id="formalooModal" tabindex="-1"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title" id="exampleModalLabel">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">بستن</button>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="formz-wrapper"
                                 data-formz-slug="EJfrsNXr"
                                 data-formz-type="simple"></div>
                            <script src="https://formaloo.com/istatic/js/main.js"></script>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
    </main>
    <!-- end Page Content -->
@endsection

@section('script')

    <script type="text/javascript">
        $(document).ready(function () {
            /*$('input:radio.custom-radio-circle-input').change(function () {
                let price = parseInt($(this).attr('data-price'));
                let stock = parseInt($(this).attr('data-stock'));
                let variant = $(this).attr('data-id');
                let value = $(this).val()
                console.log(variant);
                $(".product-price-raw").text(price.toLocaleString(undefined, {minimumFractionDigits: 0}));
                $("#variant-title").text(value);
                let url = '';
                url = url.replace(':variant', variant);
                $("a#btnAddCart").attr('href', url);

            });*/

            window.addEventListener('show-toast', event => {
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
            });

            RINO.Quantity();
            RINO.ZoomImage();
            @if(count($data->images['list']) > 0)
            RINO.GallerySlider();
            @endif

        });
    </script>
@endsection
