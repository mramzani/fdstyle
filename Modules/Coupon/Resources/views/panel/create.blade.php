@extends('dashboard::layouts.master')
@section('dashboardTitle',__('coupon::coupons.add coupon'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.css') }}">
@endsection
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="trans('coupon::coupons.add coupon')"></x-dashboard::breadcrumb>

        <!-- Brands List Table -->
        {{--@can('coupons_view')--}}
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>

            <div class="card-header">
                <h5>ایجاد کوپن تخفیف جدید</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.coupons.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="code">@lang('coupon::coupons.code')</label>
                                    <input type="text" class="form-control" value="{{ old('code') }}" name="code" id="code" placeholder="مثلا EYD1400">
                                    @error('code')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="percent">@lang('coupon::coupons.percent')</label>
                                    <input type="text" class="form-control" value="{{ old('percent') }}" name="percent" id="percent" placeholder="فقط عدد مثلا 25">
                                    @error('percent')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="limit">@lang('coupon::coupons.limit')</label>
                                    <input type="text" class="form-control" value="{{ old('limit') }}" name="limit" id="limit" placeholder="">
                                    @error('limit')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="min_basket_amount">@lang('coupon::coupons.min basket amount')</label>
                                    <input type="text" class="form-control" value="{{ old('min_basket_amount') }}" name="min_basket_amount" id="min_basket_amount" placeholder="">
                                    @error('min_basket_amount')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="allowed_qty">@lang('coupon::coupons.allowed qty')</label>
                                    <input type="text" class="form-control" value="{{ old('allowed_qty') }}" name="allowed_qty" id="allowed_qty" placeholder="">
                                    @error('allowed_qty')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="expire_date">@lang('coupon::coupons.expire date')</label>
                                    <input type="text" class="form-control expire-date" value="{{ old('expire_date') }}" name="expire_date" id="expire_date" placeholder="">
                                    @error('expire_date')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <div class="text-light small fw-semibold mb-3">@lang('coupon::coupons.status')</div>
                                    <label class="switch">
                                        <input type="checkbox" class="switch-input" value="enable" name="status" {{--@if($warehouse->status=='active') checked @endif--}} >
                                        <span class="switch-toggle-slider">
                                                          <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                          </span>
                                                          <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                          </span>
                                                        </span>
                                        <span class="switch-label">@lang('dashboard::common.enable') / @lang('dashboard::common.disable')</span>
                                    </label>
                                    @error('status')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('dashboard::common.submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{--@endcan--}}
        <!-- / Content -->
    </div>
    <!-- / Content -->
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jdate/jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/l10n/fa-jdate.js') }}"></script>
    <script>
        let flatpickrDate = $(".expire-date");
        if (flatpickrDate) {
            flatpickrDate.flatpickr({
                monthSelectorType: 'static',
                locale: 'fa',
                altInput: true,
                altFormat: 'Y/m/d',
                disableMobile: true
            });
        }
    </script>
@endsection
