<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>


        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center ">

                <div class="nav-item navbar-search-wrapper mb-0">
                    @if(array_key_exists('error',GetCreditSMS()))
                        <button type="button" class="btn btn-sm btn-outline-info">
                            {{ GetCreditSMS()['error'] }}
                        </button>
                    @else
                        @if(GetCreditSMS()['sms_credit']/10 <= 25000)
                            <button class="btn btn-sm btn-outline-danger mx-2">اعتبار پیامک رو به اتمام است لطفا با
                                پشتیبانی تماس بگیرید
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-info">اعتبار
                                پیامک {{ number_format(GetCreditSMS()['sms_credit']/10) }} تومان
                            </button>
                        @endif
                    @endif

                </div>

            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Language -->
                <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <i class="fi fi-ir fis rounded-circle fs-3 me-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-language="fa">
                                <i class="fi fi-ir fis rounded-circle fs-4 me-1"></i>
                                <span class="align-middle">@lang('dashboard::common.persian')</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" data-language="en">
                                <i class="fi fi-us fis rounded-circle fs-4 me-1"></i>
                                <span class="align-middle">@lang('dashboard::common.english')</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ Language -->

                <!-- Style Switcher -->
                <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                        <i class="bx bx-sm"></i>
                    </a>
                </li>
                <!--/ Style Switcher -->

                <!-- Quick links  -->
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                       data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="bx bx-grid-alt bx-sm"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto secondary-font">@lang('dashboard::common.shortcuts')</h5>
                                <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="افزودن میانبر"><i
                                        class="bx bx-sm bx-plus-circle"></i></a>
                            </div>
                        </div>
                        <div class="dropdown-shortcuts-list scrollable-container">
                            <div class="row row-bordered overflow-visible g-0">
                                @can('customers_view')
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-calendar fs-4"></i>
                            </span>
                                        <a href="{{ route('dashboard.customers.index') }}"
                                           class="stretched-link">@lang('dashboard::dashboard.customers')</a>
                                    </div>
                                @endcan
                                @can('suppliers_view')
                                    <div class="dropdown-shortcuts-item col">
                                        <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-food-menu fs-4"></i>
                            </span>
                                        <a href="{{ route('dashboard.suppliers.index') }}"
                                           class="stretched-link">@lang('dashboard::dashboard.supplier')</a>
                                    </div>
                                @endcan

                            </div>

                            <div class="row row-bordered overflow-visible g-0">
                                @can('users_view')
                                    <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-user fs-4"></i>
                            </span>
                                        <a href="{{ route('dashboard.users.index') }}"
                                           class="stretched-link">@lang('dashboard::dashboard.staff')</a>
                                    </div>
                                @endcan
                                @can('brands_view')
                                    <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-check-shield fs-4"></i>
                            </span>
                                        <a href="{{ route('brands.index') }}"
                                           class="stretched-link">@lang('dashboard::common.brand')</a>
                                    </div>
                                @endcan

                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                @can('categories_view')
                                    <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-pie-chart-alt-2 fs-4"></i>
                            </span>
                                        <a href="{{ route('categories.index') }}"
                                           class="stretched-link">@lang('dashboard::common.category')</a>
                                    </div>
                                @endcan
                                @can('products_view')
                                    <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-cog fs-4"></i>
                            </span>
                                        <a href="{{ route('products.index') }}"
                                           class="stretched-link">@lang('dashboard::common.products')</a>
                                    </div>
                                @endcan
                            </div>
                            <div class="row row-bordered overflow-visible g-0">
                                @can('taxes_view')
                                    <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-help-circle fs-4"></i>
                            </span>
                                        <a href="{{ route('dashboard.taxes.index') }}"
                                           class="stretched-link">@lang('dashboard::common.taxes')</a>
                                    </div>
                                @endcan
                                @can('units_view')
                                    <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-window-open fs-4"></i>
                            </span>
                                        <a href="{{ route('dashboard.units.index') }}"
                                           class="stretched-link">@lang('dashboard::common.unit')</a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </li>
                <!-- Quick links -->

                <!-- Notification -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2 d-none">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                       data-bs-auto-close="outside" aria-expanded="false">
                        <i class="bx bx-bell bx-sm"></i>
                        <span class="badge bg-danger rounded-pill badge-notifications">5</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto secondary-font">اعلان‌ها</h5>
                                <a href="javascript:void(0)" class="dropdown-notifications-all text-body"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="علامت خوانده شده به همه"><i
                                        class="bx fs-4 bx-envelope-open"></i></a>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <img src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" alt
                                                     class="w-px-40 h-auto rounded-circle">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی با</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم</p>
                                            <small class="text-muted">1 ساعت قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle bg-label-danger">اک</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با</p>
                                            <small class="text-muted">12 ساعت قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <img src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" alt
                                                     class="w-px-40 h-auto rounded-circle">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید سادگی</p>
                                            <small class="text-muted">1 ساعت قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle bg-label-success"><i
                                                        class="bx bx-cart"></i></span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی با تولید</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید</p>
                                            <small class="text-muted">1 روز قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <img src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" alt
                                                     class="w-px-40 h-auto rounded-circle">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی با تولید</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از</p>
                                            <small class="text-muted">2 روز قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle bg-label-success"><i
                                                        class="bx bx-pie-chart-alt"></i></span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی با تولید</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم</p>
                                            <small class="text-muted">3 روز قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <img src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" alt
                                                     class="w-px-40 h-auto rounded-circle">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی با</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید سادگی</p>
                                            <small class="text-muted">4 روز قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <img src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" alt
                                                     class="w-px-40 h-auto rounded-circle">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید</p>
                                            <small class="text-muted">5 روز قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar">
                                                <span class="avatar-initial rounded-circle bg-label-warning"><i
                                                        class="bx bx-error"></i></span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">لورم ایپسوم متن ساختگی</h6>
                                            <p class="mb-1">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از</p>
                                            <small class="text-muted">5 روز قبل</small>
                                        </div>
                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                            <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                    class="badge badge-dot"></span></a>
                                            <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                    class="bx bx-x"></span></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown-menu-footer border-top">
                            <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3">
                                مشاهده همه اعلان‌ها
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ Notification -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" alt
                                 class="rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online mt-1">
                                            <img src="{{ asset('assets/panel/img/avatars/empty_avatar.jpg') }}" alt
                                                 class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ user()->full_name }}</span>
                                        <small>{{ user()->roles->first()->display_name ?? 'dd' }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard.user.profile') }}">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle">پروفایل</span>
                            </a>
                        </li>
                        @can('warehouses_view')
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard.warehouses.index') }}">
                                    <i class="bx bx-cog me-2"></i>
                                    <span class="align-middle">@lang('dashboard::common.warehouses')</span>
                                </a>
                            </li>
                        @endcan

                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="document.getElementById('logoutForm').submit();">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">@lang('dashboard::common.logout')</span>
                            </a>
                            <form action="{{ route('auth.logout') }}" class="d-none" method="POST" id="logoutForm">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>

        <!-- Search Small Screens -->
        <div class="navbar-search-wrapper search-input-wrapper d-none">
            <input type="text" class="form-control search-input container-fluid border-0" placeholder="جستجو ..."
                   aria-label="Search...">
            <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
        </div>
    </div>
</nav>
