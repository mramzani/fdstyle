@extends('dashboard::layouts.master')
@section('dashboardTitle',__('coupon::coupons.coupon setting'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="trans('coupon::coupons.coupon setting')"></x-dashboard::breadcrumb>

        <!-- Brands List Table -->
        {{--@can('coupons_view')--}}
        <div class="card">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>

            <div class="card-body">
                <form action="{{ route('dashboard.coupon.setting.update') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="form-group">
                                    <div class="text-light small fw-semibold mb-3">@lang('coupon::coupons.status')</div>
                                    <label class="switch">
                                        <input type="checkbox" class="switch-input" value="enable"
                                               name="status" @if($status) checked @endif >
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
                                    @error('status')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label"
                                           for="coupon_prefix">@lang('coupon::coupons.coupon_prefix')</label>
                                    <input type="text" class="form-control" value="{{ old('coupon_prefix',$coupon_prefix) }}"
                                           name="coupon_prefix" id="coupon_prefix">
                                    @error('coupon_prefix')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label"
                                           for="coupon_percent">@lang('coupon::coupons.percent')</label>
                                    <input type="text" class="form-control" value="{{ old('coupon_percent',$coupon_percent) }}"
                                           name="coupon_percent" id="coupon_percent" placeholder="فقط عدد مثلا 25">
                                    @error('coupon_percent')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="coupon_limit">@lang('coupon::coupons.limit')</label>
                                    <input type="text" class="form-control" value="{{ old('coupon_limit',$coupon_limit) }}"
                                           name="coupon_limit" id="coupon_limit" placeholder="">
                                    @error('coupon_limit')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label"
                                           for="min_basket_amount">@lang('coupon::coupons.min basket amount')</label>
                                    <input type="text" class="form-control" value="{{ old('min_basket_amount',$min_basket_amount) }}"
                                           name="min_basket_amount" id="min_basket_amount"
                                           placeholder="">
                                    @error('min_basket_amount')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label"
                                           for="allowed_qty">@lang('coupon::coupons.allowed qty')</label>
                                    <input type="text" class="form-control" value="{{ old('allowed_qty',$allowed_qty) }}"
                                           name="allowed_qty" id="allowed_qty" placeholder="">
                                    @error('allowed_qty')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="form-group">
                                    <label class="form-label" for="deadline">@lang('coupon::coupons.deadline')</label>
                                    <input type="text" class="form-control" value="{{ old('deadline',$deadline) }}"
                                           name="deadline" id="deadline" placeholder="تعداد روز برای مهلت استفاده را وارد نمایید">
                                    @error('deadline')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">@lang('dashboard::common.update')</button>
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
