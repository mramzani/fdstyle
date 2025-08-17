@extends('dashboard::layouts.master')
@section('dashboardTitle','افزودن محصول از دیجی‌کالا')
@section('content')
    <!-- Start Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-start">
            <x-dashboard::breadcrumb breadcrumb-name="افزودن محصول از دیجی‌کالا"></x-dashboard::breadcrumb>
        </div>
        <div class="card my-2">
            <div class="card-body">
                <div class="col-12 my-1">
                    <form action="{{ route('product.smart-store') }}" method="get">
                        @csrf
                        <div class="my-3">
                            <label for="product_id" class="form-label">شناسه محصول در دیجی‌کالا</label>
                            <input type="text" id="product_id" name="product_id"
                                   class="form-control text-start"
                                   placeholder="">
                            @error('product_id')
                            <x-dashboard::validation-error></x-dashboard::validation-error>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="btn btn-outline-danger d-grid w-100 mb-3 create-product-btn"
                                    id="create-product-btn">
                                <span class="d-flex align-items-center justify-content-center text-nowrap">
                                  جستجو و افزودن محصول
                                </span>
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- End Content -->
@endsection
