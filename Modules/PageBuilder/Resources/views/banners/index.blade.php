@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت بنرها')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb breadcrumb-name="مدیریت بنرها"></x-dashboard::breadcrumb>

        <!-- Brands List Table -->
        {{--@can('brands_view')--}}
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="card-datatable table-responsive">
                {{-- @can('brands_create')--}}
                <div class="row mx-2">
                    <div class="my-2 position-relative">
                        <a href="{{ route('dashboard.banners.create') }}" class="btn btn-primary">
                           <span>
                                <i class="bx bx-plus me-0"></i>
                                <span class="d-none d-lg-inline-block">ایجاد بنر</span>
                           </span>
                        </a>
                    </div>
                </div>
                {{-- @endcan--}}

                <table class="table border-top table-responsive">
                    <thead>
                    <tr>
                        <th>نام بنر</th>
                        <th>کلید بنر</th>
                        <th>وضعیت</th>
                        <th>@lang('dashboard::common.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banners as $banner)
                        <tr>
                            <td>
                                    <span
                                        class="text-body text-truncate"><span
                                            class="fw-semibold">{{ $banner->name }}</span>
                                        </span>
                            </td>
                            <td>{{ $banner->key ?? __('dashboard::common.unknown') }}</td>

                            <td>{!! $banner->badge_status !!}</td>
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    {{-- @can('brands_edit')--}}
                                    <a href="{{ route('dashboard.banners.edit',$banner->id) }}" class="btn btn-sm btn-icon">
                                        <i class="bx bx-edit"></i></a>
                                    {{--@endcan--}}

                                    {{--@can('brands_delete')--}}
                                    <form action="{{ route('dashboard.banners.destroy',$banner->id) }}"
                                          id="deleteBannerConfirm-{{ $banner->id }}"
                                          method="post" class="btn-group">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-icon
                                                    delete-banner"
                                                data-id="{{ $banner->id }}"><i
                                                class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                    {{--@endcan--}}

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
            <div class="d-flex justify-content-center my-1">
                {{ $banners->links() }}
            </div>

        </div>
        {{-- @endcan--}}
    </div>

@endsection
@section('script')
    <script>
        $(".delete-banner").on('click', function (event) {
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
                    $("#deleteBannerConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
