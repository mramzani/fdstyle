<div class="swiper main-swiper-slider mb-4">
    <div class="swiper-wrapper">
        @foreach($home->itemSlider()->rowable->items()->orderBy('priority','desc')->get() as $slider)
            <div class="swiper-slide main-slider-item">
                <a href="{{ $slider->link ?? '#' }}" class="d-md-block d-none"><img
                        src="{{ $slider->full_with_image_url }}" alt=""></a>
                <a href="{{ $slider->link ?? '#' }}" class="d-md-none">
                    <img src="{{ $slider->mobile_image_url }}" alt=""></a>
            </div>
        @endforeach
    </div>

    <div class="swiper_pagination-navigation-custom">
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>

        <div class="swiper-navigation-container d-md-flex d-none">
            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</div>
