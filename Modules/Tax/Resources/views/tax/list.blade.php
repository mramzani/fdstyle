@extends('dashboard::layouts.master')
@section('dashboardTitle',__('dashboard::common.taxes'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="__('dashboard::common.taxes')"></x-dashboard::breadcrumb>

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
                                @lang('tax::taxes.tax list')
                            </h4>

                            <div class="card-header-elements ms-auto">
                                @can('taxes_create')
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#addTaxCanvas" aria-controls="addTaxCanvas">
                                        @lang('tax::taxes.add new tax')
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
                        @can('taxes_view')
                            @if($taxes->isEmpty())
                                <div class="alert alert-warning alert-dismissible d-flex align-items-center"
                                     role="alert">
                                    <i class="bx bx-xs bx-message-alt-error me-2"></i>
                                    @lang('tax::taxes.Unfortunately, no tax has been defined in the system yet!')
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table warehouse-list">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>@lang('tax::taxes.tax title')</th>
                                            <th>@lang('tax::taxes.tax percent')</th>
                                            <th>@lang('dashboard::common.operation')</th>
                                        </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                        @foreach($taxes as $tax)
                                            <tr>
                                                <td>{{ $tax->name }}</td>
                                                <td>%{{ $tax->rate }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        @can('taxes_edit')
                                                            <a href="{{ route('dashboard.taxes.edit',$tax) }}"
                                                               class="btn btn-sm btn-outline-primary btn-group">@lang('dashboard::common.edit')</a>
                                                        @endcan
                                                        @can('taxes_delete')
                                                            @if($tax->id != 1)
                                                                <form action="{{ route('dashboard.taxes.destroy',$tax) }}"
                                                                      id="deleteTaxesConfirm-{{ $tax->id }}"
                                                                      method="post" class="btn-group">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm
                                                                delete-tax
                                                                btn-outline-danger"
                                                                            data-id="{{ $tax->id }}"
                                                                    >@lang('dashboard::common.delete')
                                                                    </button>
                                                                </form>
                                                            @endif
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
            @canany(['taxes_create','taxes_edit'])
                <div class="offcanvas offcanvas-end" tabindex="-1" id="addTaxCanvas"
                     aria-labelledby="addTaxCanvasLabel">
                    <div class="offcanvas-header">
                        <h5 id="addTaxCanvasLabel" class="offcanvas-title">@lang('tax::taxes.add new tax')</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body my-3 mx-0 flex-grow-0">
                        <form method="post" action="{{ route('dashboard.taxes.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="name">@lang('tax::taxes.tax title')</label>
                                <input type="text" name="name" class="form-control" id=""
                                       placeholder="@lang('tax::taxes.tax title')">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="phone">@lang('tax::taxes.tax percent')</label>
                                <input type="text" name="rate" class="form-control" id="rate"
                                       placeholder="@lang('tax::taxes.tax percent')">
                            </div>

                            <button type="submit"
                                    class="btn btn-primary mb-2 d-grid w-100">@lang('tax::taxes.add new tax')</button>
                            <button type="button" class="btn btn-label-secondary d-grid w-100"
                                    data-bs-dismiss="offcanvas">
                                @lang('dashboard::common.cancel')
                            </button>
                        </form>
                    </div>
                </div>
            @endcanany
        </div>
    </div>
@endsection
@section('script')

    <script>
        $(".delete-tax").on('click', function (event) {
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
                    $("#deleteTaxesConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection

