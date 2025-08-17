<header class="page-header">
    @if(coupon_setting()->status && !Auth::guard('customer')->check())
        <div role="alert" class="alert banner-container alert-dismissible fade show">
            <a href="{{ route('front.user.show-login-form',['utm_source'=>'website','utm_medium'=>'top_banner_desktop','utm_campaign'=>'discount_first_buy']) }}" class="banner-placement rounded-0"
               style="background-image: url('{{ asset('images/banner_top_lg.jpg') }}');
        height: 50px;"></a>
        </div>
    @endif

    <div class="top-page-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="contact-list">
                    <ul>
                        <li><i class="fas fa-phone-rotary"></i><a
                                href="tel: {{ company()->phone }}">{{ company()->phone }}</a></li>
                        <li><i class="fas fa-envelope"></i><a
                                href="mailto:{{ company()->email }}">{{ company()->email }}</a></li>
                        <li><i class="fas fa-location"></i><a href="#">{{ company()->address }}</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div class="container border-bottom">
        <div class="bottom-page-header">
            <div class="d-flex align-items-center">
                <div class="site-logo">
                    <a href="{{ route('front.home') }}">
                        <img src="{{ company()->image_url }}" width="{{ config('front.logo_width') }}"
                             alt="{{ company()->site_title }}">
                    </a>
                </div>
                <div class="search-box">
                    <form action="{{ route('front.product.search') }}" method="get">
                        <input id="searchTerm" name="terms" type="text"
                               placeholder="نام محصول یا برند را جستجو کنید...">
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
            </div>
            <div class="user-items">
                {{--region Cart Section--}}
                @livewire('front::cart.cart-header')
                {{--end region Cart Section--}}
                <div class="user-item account">
                    @auth('customer')
                        <a href="#">
                            <i class="fal fa-user-circle"></i>
                            <i class="fad fa-chevron-down text-sm mr-1"></i>
                        </a>
                        <ul class="dropdown--wrapper">
                            <li class="header-profile-dropdown-account-container">
                                <a href="{{ route('front.user.profile') }}" class="d-block">
                                        <span class="header-profile-dropdown-user">
                                            <span class="header-profile-dropdown-user-img">
                                                <img src="{{ asset('assets/front/images/avatar/avatar.png') }}">
                                            </span>
                                            <span class="header-profile-dropdown-user-info">
                                                <p class="header-profile-dropdown-user-name">
                                                    {{ \Modules\User\Helper\Helper::getCustomer()->full_name  }}
                                                </p>
                                                <span class="header-profile-dropdown-user-profile-link">مشاهده حساب
                                                    کاربری</span>
                                            </span>
                                        </span>
                                </a>
                            </li>

                            <hr>
                            <li>
                                <a href="{{ route('front.user.orders') }}">
                                    سفارش‌های آنلاین
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('front.user.sales') }}">
                                    خریدهای حضوری
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('front.user.logout') }}">
                                    خروج از حساب کاربری
                                </a>
                            </li>
                        </ul>
                    @else

                        <a href="{{ route('front.user.show-login-form',['backUrl' => request()->path()]) }}"
                           class="btn-auth">
                            <i class="fal fa-user-circle"></i>
                            ورود و عضویت
                        </a>
                    @endauth


                </div>
            </div>
        </div>
        @include('front::partials.mega-menu')
    </div>
</header>
<div class="page-header-overlay"></div>
