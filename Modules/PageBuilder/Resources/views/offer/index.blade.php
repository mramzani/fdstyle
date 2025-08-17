@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت آفرها')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb breadcrumb-name="مدیریت آفرها"></x-dashboard::breadcrumb>
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="card-datatable table-responsive">
                {{-- @can('brands_create')--}}
                <div class="row mx-2">
                    <div class="my-2 position-relative">
                        <a href="{{ route('dashboard.offer.create') }}" class="btn btn-primary">
                           <span>
                                <i class="bx bx-plus me-0"></i>
                                <span class="d-none d-lg-inline-block">ایجاد آفر جدید</span>
                           </span>
                        </a>
                    </div>
                </div>
                {{-- @endcan--}}
                <table class="table border-top table-responsive">
                    <thead>
                    <tr>
                        <th>نام آفر</th>
                        <th>اسلاگ</th>
                        <th>تاریخ شروع</th>
                        <th>تاریخ پایان</th>
                        <th>وضعیت</th>
                        <th>@lang('dashboard::common.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $offer)
                        <tr>
                            <td>
                                <span class="text-body text-truncate">
                                    <a href="{{ route('offer.show',$offer->slug) }}" target="_blank"><span class="fw-semibold">{{ $offer->title }}</span></a>
                                </span>
                            </td>
                            <td>
                                <span class="text-body text-truncate">
                                    <span class="fw-semibold">{{ $offer->slug }}</span>
                                </span>
                            </td>
                            <td>{{ verta($offer->start_date)->format('j %B Y - H:i') }}</td>
                            <td>{{ verta($offer->end_date)->format('j %B Y - H:i') }}</td>
                            <td>{!! $offer->status !!}</td>
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    {{-- @can('brands_edit')--}}
                                    <a href="{{ route('dashboard.offer.edit',$offer->id) }}" class="btn btn-sm btn-icon">
                                        <i class="bx bx-edit"></i></a>
                                    {{--@endcan--}}

                                    {{--@can('brands_delete')--}}
                                    <form action="{{ route('dashboard.offer.destroy',$offer->id) }}"
                                          id="deleteOfferConfirm-{{ $offer->id }}"
                                          method="post" class="btn-group">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-icon
                                                    delete-offer"
                                                data-id="{{ $offer->id }}"><i
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
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $(".delete-offer").on('click', function (event) {
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
                        $("#deleteOfferConfirm-" + id).submit();
                    }
                });
            });
        });

    </script>
@endsection
