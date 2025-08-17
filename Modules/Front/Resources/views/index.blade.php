@extends('front::layouts.app')

@section('mainContent')
    @if($home and $home->isShowTime())
        <main class="page-content">
            @if($home->itemSlider() != null)
                @include('front::home-section.main-slider',['home' => $home])
            @endif
            <div class="container">
                @foreach($home->items()->orderBy('priority','desc')->get() as $item)
                    @if($item->rowable_type == \Modules\PageBuilder\Entities\Home::ROWABLE_TYPE['category'])
                        @include('front::home-section.product-carousel',['item' => $item,'category' => $item->rowable])
                    @elseif($item->rowable_type == \Modules\PageBuilder\Entities\Home::ROWABLE_TYPE['offer'] AND $item->rowable)
                        @include('front::home-section.promotion-section',['item' => $item])
                    @elseif($item->rowable_type == \Modules\PageBuilder\Entities\Home::ROWABLE_TYPE['banner'])
                        @if($item->rowable->type == "banner1x")
                            @include('front::home-section.banner1x',['item' => $item])
                        @elseif($item->rowable->type == "banner2x")
                            @include('front::home-section.banner2x',['item' => $item])
                        @elseif($item->rowable->type == "banner4x")
                            @include('front::home-section.banner4x',['item' => $item])
                        @endif
                    @endif
                @endforeach
            </div>
        </main>
    @else
        <main class="page-content ">
            <div class="container">
                <div class="alert alert-warning">لطفا با مراجعه به تنظیمات صفحه‌ساز فروشگاه این بخش را ویرایش کنید</div>
            </div>
        </main>
    @endif

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#countdown--offer-slider').ClassyCountdown({
                theme: "black",
                end: $.now() + 645600,
                labels: false,
            });

            if ($(".swiper-banner").length) {
                const swiper = new Swiper('.swiper-banner', {
                    // Optional parameters

                    loop: true,

                    // If we need pagination
                    pagination: {
                        el: '.swiper-pagination',
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 4,
                            spaceBetween: 40,
                        },
                        1024: {
                            slidesPerView: 5,
                            spaceBetween: 50,
                        },
                    },

                });
            }

            var swiper = new Swiper(".ri-category-container", {
                loop: true,
                slidesPerView: 7,
                spaceBetween: 20,
                // Responsive breakpoints
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    // when window width is >= 480px
                    480: {
                        slidesPerView: 3,
                        spaceBetween: 30
                    },
                    // when window width is >= 640px
                    640: {
                        slidesPerView: 4,
                        spaceBetween: 40
                    },

                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 40
                    },
                    1440: {
                        slidesPerView: 7,
                        spaceBetween: 40
                    }
                },
                // effect: "cube",
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: true,
                },
                speed: 600,
                parallax: true,
            })

            RINO.SwiperSlider();




        });
    </script>
@endsection
