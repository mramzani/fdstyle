@extends('dashboard::layouts.master')
@section('dashboardTitle',__('coupon::coupons.coupon list'))
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="trans('coupon::coupons.coupon list')"></x-dashboard::breadcrumb>

        <!-- Brands List Table -->
        {{--@can('coupons_view')--}}
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="card-datatable table-responsive">
                {{--@can('coupons_create')--}}
                <div class="row mx-2">
                    <div class="my-2 position-relative">
                        <a href="{{ route('dashboard.coupons.create') }}" class="btn btn-primary">
                           <span>
                                <i class="bx bx-plus me-0"></i>
                                <span class="d-none d-lg-inline-block">@lang('coupon::coupons.add coupon')</span>
                           </span>
                        </a>
                        <a href="{{ route('dashboard.coupon.setting.index') }}" class="btn btn-danger">
                           <span>
                                <i class="bx bx-box me-0"></i>
                                <span class="d-none d-lg-inline-block">
                                    @lang('coupon::coupons.coupon setting')
                                </span>
                           </span>
                        </a>
                    </div>
                </div>
                {{--@endcan--}}

                <table class="table border-top table-responsive">
                    <thead>
                    <tr>
                        <th>@lang('coupon::coupons.code')</th>
                        <th>@lang('coupon::coupons.limit')</th>
                        <th>@lang('coupon::coupons.percent')</th>
                        <th>@lang('coupon::coupons.min basket amount')</th>
                        <th>@lang('coupon::coupons.allowed qty')</th>
                        <th>@lang('coupon::coupons.used qty')</th>
                        <th>@lang('coupon::coupons.status')</th>
                        <th>@lang('coupon::coupons.expire date')</th>
                        <th>@lang('coupon::coupons.couponable type')</th>
                        <th>@lang('dashboard::common.operation')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ number_format($coupon->limit) }}</td>
                            <td>{{ $coupon->percent }}%</td>
                            <td>{{ number_format($coupon->min_basket_amount) }}</td>
                            <td>{{ $coupon->allowed_qty }}</td>
                            <td>{{ $coupon->used_qty }}</td>
                            <td>{!! $coupon->status_persian !!}</td>
                            <td>{{ $coupon->expire_date_jalali }}</td>
                            <td>{{ $coupon->couponable_type_persian }}</td>
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    {{--@can('coupons_edit')--}}
                                    <a href="{{ route('dashboard.coupons.edit',$coupon->id) }}"
                                       class="btn btn-sm btn-icon">
                                        <i class="bx bx-edit"></i></a>
                                    {{--@endcan--}}

                                    {{--@can('coupons_delete')--}}
                                    <form action="{{ route('dashboard.coupons.destroy',$coupon) }}"
                                          id="deleteCouponConfirm-{{ $coupon->id }}"
                                          method="post" class="btn-group">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-icon
                                                    delete-coupon"
                                                data-id="{{ $coupon->id }}"><i
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
                {{ $coupons->links() }}
            </div>

        </div>
        {{--@endcan--}}
        <!-- / Content -->
    </div>
    <!-- / Content -->
@endsection
@section('script')
    <script>
        $(".delete-coupon").on('click', function (event) {
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
                    $("#deleteCouponConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
