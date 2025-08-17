@extends('dashboard::layouts.master')
@section('dashboardTitle','تنظیمات محصولات')

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
                        <form method="POST" action="{{ route('dashboard.setting.product.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="carousel_pagination_number">تعداد محصولات کروسل
                                    چیدمان</label>
                                <input type="text" class="form-control" id="carousel_pagination_number"
                                       name="carousel_pagination_number" value="{{ $carousel_pagination_number }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="display_product_without_image">محصولات بدون تصویر در سایت نمایش داده شود؟</label>
                                <label class="switch switch-primary d-flex">
                                    <input type="checkbox" class="switch-input" id="display_product_without_image" name="display_product_without_image" value="1" @if($display_product_without_image) checked @endif>
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                      </span>
                                      <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                      </span>
                                    </span>
                                    <span class="switch-label">بله</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="product_prefix">پیشوند محصولات</label>
                                <input type="text" class="form-control" id="product_prefix" name="product_prefix"
                                       value="{{ $product_prefix }}">
                                <small>تنها یکبار این گزینه را میتوانید تغییر دهید | بعد از تعریف اولین محصول قابل تغییر
                                    نیست</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="tax_is_active">مالیات از مشتریان دریافت شود؟</label>
                                <label class="switch switch-primary d-flex">
                                    <input type="checkbox" class="switch-input" name="tax_is_active" value="1" @if($tax_is_active) checked @endif>
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                      </span>
                                      <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                      </span>
                                    </span>
                                    <span class="switch-label">بله</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="guarantee_id">گارانتی پیشفرض</label>
                                <select class="form-control" id="guarantee_id" name="guarantee_id">
                                    <option value="" selected disabled>انتخاب گارانتی محصول</option>
                                    @foreach(\Modules\Dashboard\Entities\Guarantee::all()->pluck('id','title') as $title => $id )
                                        <option value="{{ $id }}" @if($guarantee_id == $id) selected @endif>{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="preparation_time">مدت‌زمان آماده سازی پیشفرض</label>
                                <input type="text" class="form-control" id="preparation_time" name="preparation_time"
                                       placeholder="فقط عدد | به ساعت"
                                       value="{{ $preparation_time }}">
                                <small>فقط عدد به ساعت وارد کنید</small>
                            </div>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
