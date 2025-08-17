<div class="col-xl-3 col-lg-4 col-md-4 mb-lg-0 mb-4 ">
    <div class="nav-align-left border rounded p-2">
        <ul class="nav nav-pills w-100 gap-1 my-2">
            @can('general_setting_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.setting.general') }}"
                       class="nav-link @if(Route::is('dashboard.setting.general')) active @endif" >@lang('dashboard::common.general setting')</a>
                </li>
            @endcan
            {{--@can('show_self_profile')--}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.setting.product.edit') }}"
                       class="nav-link @if(Route::is('dashboard.setting.product.edit')) active @endif">@lang('dashboard::common.product setting')</a>
                </li>
            {{--@endcan--}}

            {{--@can('warehouses_view')--}}
                <li class="nav-item d-none">
                    <a href="#"
                       class="nav-link">@lang('dashboard::common.factor setting')</a>
                </li>
            {{--@endcan--}}

            @can('shipping_setting_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.setting.shipping.edit') }}"
                       class="nav-link @if(Route::is('dashboard.setting.shipping.edit')) active @endif">@lang('dashboard::common.shipping setting')</a>
                </li>
            @endcan

            {{--@can('taxes_view')--}}
                <li class="nav-item d-none">
                    <a href="#"
                       class="nav-link">@lang('dashboard::common.orders setting')</a>
                </li>
            {{--@endcan--}}

            @can('third_party_setting_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.setting.third-party.edit') }}"
                       class="nav-link @if(Route::is('dashboard.setting.third-party.edit')) active @endif" >@lang('dashboard::common.third party setting')</a>
                </li>
            @endcan

            <li class="nav-item d-none">
                <a class="nav-link" href="#">@lang('dashboard::common.notification setting')</a>
            </li>

        </ul>
    </div>
</div>
