<div class="col-lg-3 col-md-4 mb-md-0 mb-3 sticky-sidebar">
    <div class="sidebar-widget">
        <div class="profile-sidebar shadow-around">
            <div class="profile-sidebar-header">
                <div class="d-flex align-items-center">
                    <div class="profile-avatar">
                        <img src="{{ asset('assets/front/images/avatar/avatar.png') }}" alt="avatar">
                    </div>
                    <div class="profile-info">
                        <div class="d-block">

                            <h6>
                                <strong>{{ Helper::getCustomer()->full_name ?? 'نامشخص' }}</strong>
                            </h6>
                        </div>
                        <div class="d-block">
                            <strong class="text-muted">{{ Helper::getCustomer()->mobile ?? 'نامشخص' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="border-bottom my-2"></div>
            </div>
            <div class="user-menu">
                <ul>
                    <li>
                        <a href="{{ route('front.user.profile') }}"
                           class="@if(\Route::is('front.user.profile')) active @endif">
                            <i class="far fa-home"></i>
                            خلاصه فعالیت ها
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.user.orders') }}" @if(Route::is('front.user.orders')) class="active" @endif >
                            <i class="far fa-clipboard-list-check"></i>
                            سفارش‌های من
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.user.sales') }}" @if(Route::is('front.user.sales')) class="active" @endif>
                            <i class="far fa-shopping-basket"></i>
                            خرید‌های حضوری
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.user.profile.address.list') }}"
                           class="@if(\Route::is('front.user.profile.address.list')) active @endif">
                            <i class="far fa-map-marked"></i>
                            آدرس‌های من
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.user.profile.personal-info-form') }}"
                           class="@if(\Route::is('front.user.profile.personal-info-form')) active @endif">
                            <i class="far fa-user-cog"></i>
                            اطلاعات حساب
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('front.user.logout') }}">
                            <i class="far fa-sign-in-alt"></i>
                            خروج
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
