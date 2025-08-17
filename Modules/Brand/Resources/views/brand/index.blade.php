@extends('dashboard::layouts.master')
@section('dashboardTitle',__('brand::brands.brand list'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="trans('brand::brands.brand list')"></x-dashboard::breadcrumb>

        <!-- Brands List Table -->
        @can('brands_view')
            <div class="card">
                <div class="m-2">
                    @include('dashboard::partials.alert')
                </div>
                <div class="card-datatable table-responsive">
                    @can('brands_create')
                        <div class="row mx-2">
                            <div class="my-2 position-relative">
                                <a href="{{ route('brands.create') }}" class="btn btn-primary">
                           <span>
                                <i class="bx bx-plus me-0"></i>
                                <span class="d-none d-lg-inline-block">@lang('brand::brands.add brand')</span>
                           </span>
                                </a>
                            </div>
                        </div>
                    @endcan

                    <table class="table border-top table-responsive">
                        <thead>
                        <tr>
                            <th>@lang('brand::brands.title_fa')</th>
                            <th>@lang('brand::brands.title_en')</th>
                            <th>@lang('brand::brands.slug')</th>
                            <th>@lang('dashboard::common.operation')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($brands as $brand)
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-md me-3">
                                                <img class="rounded"
                                                     src="{{ $brand->image_url }}">
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                        <span
                                            class="text-body text-truncate"><span
                                                class="fw-semibold">{{ $brand->title_fa }}</span>
                                        </span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $brand->title_en ?? __('dashboard::common.unknown') }}</td>
                                <td>{{ $brand->slug }}</td>
                                <td>
                                    <div class="d-inline-block text-nowrap">
                                        @can('brands_edit')
                                            <a href="{{ route('brands.edit',$brand->id) }}" class="btn btn-sm btn-icon">
                                                <i class="bx bx-edit"></i></a>
                                        @endcan

                                        @can('brands_delete')
                                            <form action="{{ route('brands.destroy',$brand) }}"
                                                  id="deleteBrandConfirm-{{ $brand->id }}"
                                                  method="post" class="btn-group">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-icon
                                                    delete-brand"
                                                        data-id="{{ $brand->id }}"><i
                                                        class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @endcan

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="d-flex justify-content-center my-1">
                    {{ $brands->links() }}
                </div>

            </div>
        @endcan
    </div>
    <!-- / Content -->
@endsection
@section('script')
    <script>
        $(".delete-brand").on('click', function (event) {
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
                    $("#deleteBrandConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
