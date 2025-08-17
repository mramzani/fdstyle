@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('warehouse::warehouses.warehouse list'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb
            :breadcrumb-name="trans('warehouse::warehouses.warehouse list')"></x-dashboard::breadcrumb>

        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.categories')
            <!-- /Categories -->

            <!-- Warehouse list -->
            <div class="col-xl-9 col-lg-8 col-md-8">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="card-header header-elements">
                            <h4 class="d-flex align-items-center">
                                    <span class="badge bg-label-secondary p-2 rounded me-3">
                                      <i class="bx bx-cube bx-sm"></i>
                                    </span>
                                @lang('warehouse::warehouses.warehouse list')
                            </h4>

                            <div class="card-header-elements ms-auto">
                                @can('warehouses_create')
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#addWarehouseCanvas" aria-controls="addWarehouseCanvas">
                                        @lang('warehouse::warehouses.add new warehouse')
                                    </button>
                                @endcan
                            </div>
                        </div>
                        @include('dashboard::partials.alert')
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @can('warehouses_view')
                            @if($warehouses->isEmpty())
                                <div class="alert alert-warning alert-dismissible d-flex align-items-center" role="alert">
                                    <i class="bx bx-xs bx-message-alt-error me-2"></i>
                                    @lang('warehouse::warehouses.Unfortunately, no tax has been defined in the system yet!')
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table warehouse-list">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>@lang('warehouse::warehouses.warehouse name')</th>
                                            <th>@lang('warehouse::warehouses.warehouse phone')</th>
                                            <th>@lang('warehouse::warehouses.warehouse address')</th>
                                            <th>@lang('warehouse::warehouses.warehouse status')</th>
                                            <th>@lang('dashboard::common.operation')</th>
                                        </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                        @foreach($warehouses as $warehouse)
                                            <tr>
                                                <td>{{ $warehouse->name }}</td>
                                                <td>{{ $warehouse->phone }}</td>
                                                <td>{{ $warehouse->address }}</td>
                                                <td>{!! $warehouse->status_persian !!}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        @can('warehouses_edit')
                                                            <a href="{{ route('dashboard.warehouses.edit',$warehouse) }}"
                                                               class="btn btn-sm btn-outline-primary btn-group">@lang('dashboard::common.edit')</a>
                                                        @endcan
                                                        @can('warehouses_delete')
                                                                <form
                                                                    action="{{ route('dashboard.warehouses.destroy',$warehouse) }}"
                                                                    id="deleteWarehouseConfirm-{{ $warehouse->id }}"
                                                                    method="post" class="btn-group">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            data-id="{{ $warehouse->id }}"
                                                                            class="btn btn-sm
                                                                    delete-warehouse
                                                                    btn-outline-danger"
                                                                    >@lang('dashboard::common.delete')</button>
                                                                </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endcan

                    </div>

                </div>
            </div>
            <!-- /Warehouse list -->

            @canany(['warehouses_create','warehouses_edit'])
                <!-- Start add warehouse offCanvas -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="addWarehouseCanvas"
                     aria-labelledby="addWarehouseCanvasLabel">
                    <div class="offcanvas-header">
                        <h5 id="addWarehouseCanvasLabel"
                            class="offcanvas-title">@lang('warehouse::warehouses.add new warehouse')</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body my-3 mx-0 flex-grow-0">
                        <form method="post" action="{{ route('dashboard.warehouses.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">@lang('warehouse::warehouses.warehouse name')</label>
                                <input type="text" name="name" class="form-control" id=""
                                       placeholder="@lang('warehouse::warehouses.warehouse name')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="phone">@lang('warehouse::warehouses.warehouse phone')</label>
                                <input type="text" name="phone" class="form-control" maxlength="11" id="phone"
                                       placeholder="@lang('warehouse::warehouses.warehouse phone')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"
                                       for="address">@lang('warehouse::warehouses.warehouse address')</label>
                                <input type="text" name="address" class="form-control" id="address"
                                       placeholder="@lang('warehouse::warehouses.warehouse address')">
                            </div>
                            <div class="mb-3">
                                <div
                                    class="text-light small fw-semibold mb-3">@lang('warehouse::warehouses.warehouse status')</div>
                                <label class="switch">
                                    <input type="checkbox" class="switch-input" name="status" value="active">
                                    <span class="switch-toggle-slider">
                                                          <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                          </span>
                                                          <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                          </span>
                                                        </span>
                                    <span
                                        class="switch-label">@lang('dashboard::common.enable') / @lang('dashboard::common.disable')</span>
                                </label>
                            </div>

                            <button type="submit"
                                    class="btn btn-primary mb-2 d-grid w-100">@lang('warehouse::warehouses.add new warehouse')</button>
                            <button type="button" class="btn btn-label-secondary d-grid w-100"
                                    data-bs-dismiss="offcanvas">
                                @lang('dashboard::common.cancel')
                            </button>
                        </form>
                    </div>
                </div>
                <!-- /End add warehouse offCanvas -->
            @endcanany

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(".delete-warehouse").on('click', function (event) {
            event.preventDefault();
            const btn = $(this);
            let id = $(this).data("id");
            Swal.fire({
                title: "@lang('dashboard::common.Are you sure to delete?')",
                text: "@lang('dashboard::common.If you delete the information, it will be lost!')",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "@lang('dashboard::common.confirm')",
                cancelButtonText: "@lang('dashboard::common.cancel')",
                buttonsStyling: false,
            }).then(function (value) {
                if (!value.dismiss) {
                    $("#deleteWarehouseConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection

