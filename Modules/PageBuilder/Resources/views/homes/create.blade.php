@extends('dashboard::layouts.master')
@section('dashboardTitle','ایجاد چیدمان جدید')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.css') }}">
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('dashboard.home.store') }}" method="post">
            @csrf
            <div class="row">
                @include('dashboard::partials.alert')
                <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label input-required" for="name">نام چیدمان</label>
                                <input type="text" class="form-control @error('name') border-danger @enderror"
                                       value="{{ old('name','') }}" id="name" name="name" placeholder="نام صفحه به دلخواه وارد کنید">
                                @error('name')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label input-required" for="start_at">تاریخ شروع نمایش</label>
                                    <input type="text" class="form-control flatpickr-datetime @error('start_at') border-danger @enderror"
                                           value="{{ old('start_at','') }}" id="start_at" name="start_at">
                                    @error('start_at')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label input-required" for="expire_at">تاریخ پایان نمایش</label>
                                    <input type="text" class="form-control flatpickr-datetime @error('expire_at') border-danger @enderror"
                                           value="{{ old('expire_at','') }}" id="expire_at" name="expire_at">
                                    @error('expire_at')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-4 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">وضعیت</label>
                                <select class="form-select" id="status" name="status">
                                    @foreach(\Modules\PageBuilder\Entities\Home::STATUS as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check my-3">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default">
                                <label class="form-check-label" for="is_default">چیدمان پیشفرض</label>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                            <span class="d-flex align-items-center justify-content-center text-nowrap">ذخیره و ویرایش</span>
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </form>

    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jdate/jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/l10n/fa-jdate.js') }}"></script>
    <script type="text/javascript" >
        let flatpickrDatetime = $(".flatpickr-datetime");
        if (flatpickrDatetime) {
            flatpickrDatetime.flatpickr({
                enableTime: true,
                locale: 'fa',
                altInput: true,
                altFormat: 'Y/m/d - H:i',
                disableMobile: true,
            });
        }

    </script>
@endsection
