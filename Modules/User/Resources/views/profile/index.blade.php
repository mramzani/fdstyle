@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('dashboard::common.profile'))
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('dashboard::partials.breadcrumb')

        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.categories')
            <!-- /Categories -->

            <!-- Warehouse list -->
            <div class="col-xl-9 col-lg-8 col-md-8">
                @include('dashboard::partials.alert')
                <div class="card overflow-hidden">

                    <div class="card-body">
                        <form action="{{ route('dashboard.user.profile.save') }}" id="formAccountSettings" method="POST" >
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">@lang('user::user.first_name')</label>
                                    <input class="form-control" type="text" id="firstName" name="first_name" value="{{ old('first_name',$user->first_name) ?? '' }}" autofocus>
                                    @error('first_name')
                                        <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="lastName" class="form-label">@lang('user::user.last_name')</label>
                                    <input class="form-control" type="text" name="last_name" id="lastName" value="{{ old('last_name',$user->last_name) ?? '' }}">
                                    @error('last_name')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="national_code" class="form-label">@lang('user::user.national_code')</label>
                                    <input type="text" class="form-control" id="national_code" name="national_code" value="{{ $user->national_code ?? '' }}">
                                    @error('national_code')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">@lang('user::user.email')</label>
                                    <input class="form-control text-start" dir="ltr" type="text" id="email" name="email" value="{{ $user->email ?? '' }}" placeholder="email@example.com">
                                    @error('email')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label" for="mobile">@lang('user::user.mobile')</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="mobile" name="mobile" class="form-control text-start" disabled dir="ltr" value="{{ $user->mobile ?? '' }}">
                                        <span class="input-group-text">IR (+98)</span>
                                    </div>
                                    @error('mobile')
                                    <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <div class="text-light small fw-semibold mb-3">@lang('user::user.status')</div>
                                    <label class="switch">
                                        <input type="checkbox" class="switch-input" disabled
                                               name="status" @if($user->status=='active') checked @endif >
                                        <span class="switch-toggle-slider">
                                                          <span class="switch-on">
                                                            <i class="bx bx-check"></i>
                                                          </span>
                                                          <span class="switch-off">
                                                            <i class="bx bx-x"></i>
                                                          </span>
                                                        </span>
                                        <span class="switch-label">@lang('dashboard::common.disable') / @lang('dashboard::common.enable') </span>
                                    </label>
                                    @error('status')
                                        <x-dashboard::validation-error :message="$message"></x-dashboard::validation-error>
                                    @enderror
                                </div>

                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">@lang('dashboard::common.submit')</button>
                                <a href="#reset" class="btn btn-label-secondary">@lang('dashboard::common.cancel')</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- /Warehouse list -->
        </div>
    </div>
@endsection
