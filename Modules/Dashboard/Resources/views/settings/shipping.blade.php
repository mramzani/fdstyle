@extends('dashboard::layouts.master')
@section('dashboardTitle','تنظیمات حمل‌ونقل')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- <x-dashboard::breadcrumb :breadcrumb-name="trans('unit::units.units')"></x-dashboard::breadcrumb>--}}

        <div class="row">
            <!-- Categories -->
            @include('dashboard::partials.sections.setting-menu')
            <!-- /Categories -->

            <div class="col-xl-9 col-lg-8 col-md-8">
                <div class="">
                    @if ($errors->any())
                        <div class="alert alert-warning">
                            <span>لطفا خطاهای زیر را بررسی نمایید</span>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="list-inline">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @include('dashboard::partials.alert')
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.setting.shipping.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="shipping_cost">هزینه ارسال با پست</label>
                                <input type="text" class="form-control" id="shipping_cost" name="shipping_cost" value="{{ $shipping_cost }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="shipping_free_cost">آیا ارسال رایگان دارید؟</label>
                                <input type="text" class="form-control" id="shipping_free_cost" name="shipping_free_cost" value="{{ $shipping_free_cost }}">
                                <small>بالاتر از این مبلغ ارسال رایگان است | درصورتی که نمی‌خواهید ارسال رایگان داشته باشید مقدار 0 قرار دهید</small>
                            </div>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
