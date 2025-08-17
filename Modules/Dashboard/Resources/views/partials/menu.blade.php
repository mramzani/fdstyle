<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">@lang('dashboard::dashboard.main programs')</span>
        </li>
        <!--Dashboard menu -->
        @can('dashboard_view_menu')
            <li class="menu-item  {{ \Route::is(['dashboard.index']) ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-dashboard"></i>
                    <div>@lang('dashboard::dashboard.dashboard')</div>
                </a>
            </li>
        @endcan
        <!--People menu -->
        @can('people_manage')
            <li class="menu-item
                @if(\Route::is(['dashboard.customers.index','dashboard.suppliers.index','dashboard.users.index']))
                open
                @endif
                ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>@lang('dashboard::dashboard.people management')</div>
                </a>
                <ul class="menu-sub">
                    @can('customers_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.customers.index') }}" class="menu-link">
                                <div>@lang('dashboard::dashboard.customers')</div>
                            </a>
                        </li>
                    @endcan
                    @can('suppliers_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.suppliers.index') }}" class="menu-link">
                                <div>@lang('dashboard::dashboard.supplier')</div>
                            </a>
                        </li>
                    @endcan
                    @can('users_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.users.index') }}" class="menu-link">
                                <div>@lang('dashboard::dashboard.staff')</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <!--Product menu -->
        @can('products_manage_menu')
            <li class="menu-item
                @if(Route::is(['categories.index','brands.index','products.index','variants.index']))
                open
                @endif
                ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div>@lang('dashboard::dashboard.products management')</div>
                </a>
                <ul class="menu-sub">
                    @can('brands_view')
                        <li class="menu-item">
                            <a href="{{ route('brands.index') }}" class="menu-link">
                                <div>@lang('dashboard::common.brand list')</div>
                            </a>
                        </li>
                    @endcan
                    @can('categories_view')
                        <li class="menu-item">
                            <a href="{{ route('categories.index') }}" class="menu-link">
                                <div>@lang('dashboard::common.category list')</div>
                            </a>
                        </li>
                    @endcan
                    @can('products_view')
                        <li class="menu-item">
                            <a href="{{ route('products.index') }}" class="menu-link">
                                <div>@lang('dashboard::common.products list')</div>
                            </a>
                        </li>
                    @endcan
                    @can('attributes_view')
                        <li class="menu-item ">
                            <a href="{{ route('variants.index') }}" class="menu-link">
                                <div>@lang('dashboard::common.variants management')</div>
                            </a>
                        </li>
                    @endcan
                    @can('attribute_group_view')
                        <li class="menu-item">
                            <a href="{{ route('attribute-group.index') }}" class="menu-link">
                                <div>@lang('dashboard::common.attribute groups')</div>
                            </a>
                        </li>
                    @endcan
                    @can('size_guide_view')
                        <li class="menu-item ">
                            <a href="{{ route('guide-size.index') }}" class="menu-link">
                                <div>مدیریت راهنمای سایز</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        <!--Sales menu -->
        @can('stock_adjustments_view')
            <li class="menu-item
                @if(Route::is(['adjustments.index','barcode.print']))
                open
                @endif
                ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-list-ol"></i>
                    <div>@lang('dashboard::common.inventory adjustment and barcode')</div>
                </a>
                <ul class="menu-sub">
                    @can('stock_adjustments_view')
                        <li class="menu-item">
                            <a href="{{ route('adjustments.index') }}" class="menu-link">
                                <div>@lang('dashboard::common.inventory adjustment list')</div>
                            </a>
                        </li>
                    @endcan
                    @can('show_print_barcode')
                        <li class="menu-item">
                            <a href="{{ route('barcode.print') }}" class="menu-link">
                                <div>@lang('dashboard::common.barcode print')</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('pos_view')
            <li class="menu-item bg-label-primary">
                <a href="{{ route('dashboard.pos') }}" target="_blank" class="menu-link text-success">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div>صندوق فروش</div>
                </a>
            </li>
        @endcan
        <!--Expense menu -->
        @can('sales_view')
            <li class="menu-item
                @if(\Route::is(['dashboard.sales.index']))
                open
                @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-basket"></i>
                    <div>فروش‌های من</div>
                </a>
                <ul class="menu-sub">
                    @can('sales_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.sales.index') }}" class="menu-link">
                                <div>لیست فروش</div>
                            </a>
                        </li>
                    @endcan


                    <li class="menu-item d-none">
                        <a href="#" class="menu-link">
                            <div>برگشت از فروش</div>
                        </a>
                    </li>
                    @can('order_payments_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.payment.index','in') }}" class="menu-link">
                                <div>لیست تراکنش دریافتی</div>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan
        <!--Purchase menu -->
        @can('purchases_view')
            <li class="menu-item
                @if(\Route::is(['dashboard.purchase.index','dashboard.purchase.create']))
                open
                @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-import"></i>
                    <div>خریدهای من</div>
                </a>
                <ul class="menu-sub">
                    @can('purchases_create')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.purchase.create') }}" class="menu-link">
                                <div>ثبت سفارش خرید جدید</div>
                            </a>
                        </li>
                    @endcan

                    @can('purchases_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.purchase.index') }}" class="menu-link">
                                <div>لیست خرید</div>
                            </a>
                        </li>
                    @endcan

                    @can('order_payments_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.payment.index','out') }}" class="menu-link">
                                <div>لیست تراکنش پرداختی</div>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan
        <!--Pos menu -->
        @can('online_shop_menu')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-desktop"></i>
                    <div>فروشگاه آنلاین</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        @can('order_view')
                            <a href="{{ route('dashboard.online-order.index') }}" class="menu-link">
                                <div>لیست سفارشات آنلاین</div>
                            </a>
                        @endcan
                        @can('view_transaction')
                            <a href="{{ route('dashboard.transactions.list') }}" class="menu-link">
                                <div>تراکنش‌های آنلاین</div>
                            </a>
                        @endcan

                        @can('coupon_list')
                            <a href="{{ route('dashboard.coupons.index') }}" class="menu-link">
                                <div>کوپن تخفیف</div>
                            </a>
                        @endcan

                        @can('view_merchants')
                            <a href="{{ route('dashboard.checkout.index') }}" class="menu-link">
                                <div>مالی + تسویه‌حساب</div>
                            </a>
                        @endcan
                    </li>
                </ul>
            </li>
        @endcan
        @can('page_builder')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-desktop"></i>
                    <div>ظاهر سایت</div>
                </a>
                <ul class="menu-sub">
                    @can('view_front_menu')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.menus.index') }}" class="menu-link">
                                <div data-i18n="Account">چیدمان منو</div>
                            </a>
                        </li>
                    @endcan
                    <li class="menu-item">
                        <a href="{{ route('dashboard.home.index') }}" class="menu-link">
                            <div data-i18n="Account">چیدمان صفحه اول</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dashboard.banners.index') }}" class="menu-link">
                            <div data-i18n="Account">مدیریت بنر</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dashboard.sliders.index') }}" class="menu-link">
                            <div data-i18n="Account">مدیریت اسلایدر</div>
                        </a>
                    </li>
                    @can('show_pages_menu')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.page.index') }}" class="menu-link">
                                <div data-i18n="Account">مدیریت صفحات</div>
                            </a>
                        </li>
                    @endcan

                    <li class="menu-item">
                        <a href="{{ route('dashboard.offer.index') }}" class="menu-link">
                            <div data-i18n="Account">مدیریت آفرها</div>
                        </a>
                    </li>

                </ul>
            </li>
        @endcan
        @can('expenses_view')
            <li class="menu-item d-none">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-export"></i>
                    <div>هزینه‌ها</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>دسته‌بندی هزینه</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <div>هزینه‌ها</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan


        <!--Report menu -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-chart"></i>
                <div>گزارشات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('dashboard.reports.profit-loss') }}" class="menu-link">
                        <div>سود و زیان</div>
                    </a>
                </li>
            </ul>
        </li>
        {{--@can('reports_view')--}}

        {{--@endcan--}}
        <!--Setting menu -->
        @can('setting_view_menu')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div>@lang('dashboard::common.setting')</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('dashboard.user.profile') }}" class="menu-link">
                            <div>اطلاعات پایه</div>
                        </a>
                    </li>
                    @can('general_setting_view')
                        <li class="menu-item">
                            <a href="{{ route('dashboard.setting.general') }}" class="menu-link">
                                <div>تنظیمات سیستم</div>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan
        <!--Logout menu -->
        <li class="menu-item">
            <a href="{{ route('front.home') }}" class="menu-link" target="_blank">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div>مشاهده سایت</div>
            </a>
        </li>
        @can('logout_menu')
            <li class="menu-item">
                <a href="#" onclick="document.getElementById('logoutForm').submit();" class="menu-link text-danger">
                    <i class="menu-icon tf-icons bx bx-power-off"></i>
                    <div>@lang('dashboard::common.logout')</div>
                </a>
                <form action="{{ route('auth.logout') }}" class="d-none" method="POST" id="logoutForm">
                    @csrf
                </form>
            </li>
        @endcan
    </ul>
</aside>
