@if(coupon_setting()->status && !Auth::guard('customer')->check())
    <div role="alert" class="alert banner-container alert-dismissible fade show">
        <a href="{{ route('front.user.show-login-form',['utm_source'=>'website','utm_medium'=>'top_banner_mobile','utm_campaign'=>'discount_first_buy']) }}" class="banner-placement rounded-0"
           style="background-image: url('{{ asset('images/banner_top_sm.jpg') }}');
        height: 40px;width: auto"></a>
    </div>
@endif
<div class="header-responsive fixed--header-top">
    <div class="header-top">
        <div class="side-navigation-wrapper">
            <button class="btn-toggle-side-navigation"><i class="far fa-bars"></i></button>
            <div class="side-navigation">
                <div class="site-logo">
                    <a href="{{ route('front.home') }}">
                        <img src="{{ company()->image_url }}" width="{{ config('front.logo_width') }}"
                             alt="{{ company()->site_title }}">
                    </a>
                </div>
                <div class="divider"></div>

                <ul class="category-list">
                    @foreach(\Modules\Menu\Entities\Menu::where('parent_id',null)->orderBy('id','asc')->get() as $menu)
                        <li @if(count($menu->children) > 0) class="has-children" @endif>
                            <a href="{{ $menu->url }}">{{ $menu->title }}</a>
                            @if(count($menu->children) > 0)
                                @include('front::partials.responsive-menu-child',['menus' => $menu->children])
                            @endif
                        </li>
                    @endforeach

                </ul>
            </div>
            <div class="overlay-side-navigation"></div>
        </div>
        <div class="site-logo">
            <a href="{{ route('front.home') }}">
                <img src="{{ company()->image_url }}" width="{{ config('front.logo_width') }}"
                     alt="{{ company()->site_title }}">
            </a>
        </div>
        <div class="header-tools">
            @livewire('front::cart.responsive-cart')
        </div>
    </div>
    <div class="header-bottom">
        <div class="search-box">
            <form action="{{ route('front.product.search') }}" method="get">
                <input type="text" name="terms" id="searchTerm" placeholder="نام محصول یا برند را جستجو کنید...">
                <i class="far fa-search"></i>
            </form>
            <div class="search-result d-none">
                <ul class="search-result-list">
                    <li><a href="#">موبایل</a></li>
                    <li><a href="#">سامسونگ</a></li>
                    <li><a href="#">مودم</a></li>
                    <li><a href="#">اپل</a></li>
                    <li><a href="#">تلویزیون</a></li>
                </ul>
                <ul class="search-result-most-view">
                    <li><a href="#">موبایل</a></li>
                    <li><a href="#">سامسونگ</a></li>
                    <li><a href="#">مودم</a></li>
                    <li><a href="#">اپل</a></li>
                    <li><a href="#">تلویزیون</a></li>
                </ul>
            </div>
        </div>
        <div class="header-tools">
            <a href="{{ route('front.user.profile') }}"><i class="far fa-user-circle"></i></a>
        </div>
    </div>
</div>
