<div class="col-xl-3 col-lg-4 col-md-4 mb-lg-0 mb-4">
    <div class="nav-align-left border rounded p-2">
        <ul class="nav nav-pills w-100 gap-1 my-2">
            @can('companies_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.company.edit') }}"
                       class="nav-link
                   {{ \Route::is('dashboard.company.edit') ? 'active' : '' }}">@lang('dashboard::common.company information')</a>
                </li>
            @endcan
            @can('show_self_profile')
                <li class="nav-item">
                    <a href="{{ route('dashboard.user.profile') }}"
                       class="nav-link
                   {{ \Route::is('dashboard.user.profile') ? 'active' : '' }}">@lang('dashboard::common.profile')</a>
                </li>
            @endcan

            @can('warehouses_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.warehouses.index') }}"
                       class="nav-link
                   {{ \Route::is('dashboard.warehouses.index') ? 'active' : '' }}">@lang('dashboard::common.warehouses')</a>
                </li>
            @endcan

            @can('roles_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.roles.index') }}"
                       class="nav-link
                   {{ \Route::is('dashboard.roles.index') ? 'active' : '' }}">@lang('dashboard::common.roles permissions')</a>
                </li>
            @endcan

            @can('taxes_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.taxes.index') }}"
                       class="nav-link
                   {{ \Route::is('dashboard.taxes.index') ? 'active' : '' }}">@lang('dashboard::common.taxes')</a>
                </li>
            @endcan

            <li class="nav-item d-none">
                <button class="nav-link"
                        data-bs-target="javascript:void(0);">@lang('dashboard::common.currency')</button>
            </li>

            @can('units_view')
                <li class="nav-item">
                    <a href="{{ route('dashboard.units.index') }}" class="nav-link
                {{ \Route::is('dashboard.units.index') ? 'active' : '' }}
                " data-bs-target="javascript:void(0);">@lang('dashboard::common.unit')</a>
                </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link @if(\Route::is('dashboard.guarantees.index')) active @endif"
                   href="{{ route('dashboard.guarantees.index') }}">@lang('dashboard::common.guarantee')</a>
            </li>
            <li class="nav-item d-none">
                <button class="nav-link"
                        data-bs-target="javascript:void(0);">@lang('dashboard::common.pay gateway')</button>
            </li>
        </ul>
    </div>
</div>
