@extends('dashboard::layouts.master')
@section('dashboardTitle',__('category::categories.categories list'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/panel/vendor/libs/nestable/jquery.nestable.min.css') }}">
@stop
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb
            :breadcrumb-name="__('category::categories.categories list')"></x-dashboard::breadcrumb>
        <!-- Category List Table -->
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="card-datatable table-responsive">
                <div class="row mx-2">
                    @can('categories_create')
                        <div class="my-2 position-relative">
                            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                           <span>
                                <i class="bx bx-plus me-0"></i>
                                <span class="d-none d-lg-inline-block">@lang('category::categories.add category')</span>
                           </span>
                            </a>
                        </div>
                    @endcan
                    @can('categories_view')
                        <div class="dd">
                            @include('category::category.common.collapse-group')
                        </div>
                    @endcan

                </div>
            </div>

        </div>
        <!-- End of Category List Table -->
    </div>
@endsection
@section('script')
    <script>
        $(".delete-category").on('click', function (event) {
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
                    $("#deleteConfirmCategory-" + id).submit();
                }
            });
        });
    </script>
@endsection
