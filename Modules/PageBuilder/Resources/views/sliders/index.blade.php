@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت اسلایدرها')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb breadcrumb-name="مدیریت اسلایدرها"></x-dashboard::breadcrumb>

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
                                <a href="{{ route('dashboard.sliders.create') }}" class="btn btn-primary">
                           <span>
                                <i class="bx bx-plus me-0"></i>
                                <span class="d-none d-lg-inline-block">ایجاد اسلایدر</span>
                           </span>
                                </a>
                            </div>
                        </div>
                   {{-- @endcan--}}

                    <table class="table border-top table-responsive">
                        <thead>
                        <tr>
                            <th>نام اسلایدر</th>
                            <th>کلید اسلایدر</th>
                            {{--<th>تاریخ شروع نمایش</th>
                            <th>تاریخ پایان نمایش</th>--}}
                            <th>تعداد تصویر</th>
                            <th>وضعیت</th>
                            <th>@lang('dashboard::common.operation')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td>
                                    <span
                                        class="text-body text-truncate"><span
                                            class="fw-semibold">{{ $slider->name }}</span>
                                        </span>
                                </td>
                                <td>{{ $slider->key ?? __('dashboard::common.unknown') }}</td>
                                {{--<td>{{ $slider->jalali_start_date }}</td>
                                <td>{{ $slider->jalali_end_date }}</td>--}}
                                <td>{{ count($slider->items) }}</td>
                                <td>{!! $slider->badge_status !!}</td>
                                <td>
                                    <div class="d-inline-block text-nowrap">
                                       {{-- @can('brands_edit')--}}
                                            <a href="{{ route('dashboard.sliders.edit',$slider->id) }}" class="btn btn-sm btn-icon">
                                                <i class="bx bx-edit"></i></a>
                                        {{--@endcan--}}

                                        {{--@can('brands_delete')--}}
                                            <form action="{{ route('dashboard.sliders.destroy',$slider->id) }}"
                                                  id="deleteSliderConfirm-{{ $slider->id }}"
                                                  method="post" class="btn-group">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-icon
                                                    delete-slider"
                                                        data-id="{{ $slider->id }}"><i
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
                    {{ $sliders->links() }}
                </div>

            </div>
       {{-- @endcan--}}
    </div>
@endsection

@section('script')
    <script>
        $(".delete-slider").on('click', function (event) {
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
                    $("#deleteSliderConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
