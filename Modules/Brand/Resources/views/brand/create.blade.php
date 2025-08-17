@extends('dashboard::layouts.master')
@section('dashboardTitle',__('brand::brands.add brand'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="__('brand::brands.add brand')"></x-dashboard::breadcrumb>
        <div class="card mb-4">
            <div class="card-body">
                @can('brands_create')
                    <form action="{{ route('brands.store') }}" method="post" class="row g-3"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-2">
                            <label class="form-label w-100" for="brandName">@lang('brand::brands.title_fa')</label>
                            <div class="input-group input-group-merge">
                                <input id="brandName" name="title_fa" class="form-control" type="text">
                            </div>
                            @error('title_fa')
                            <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label w-100" for="brandTitleEn">@lang('brand::brands.title_en')</label>
                            <div class="input-group input-group-merge">
                                <input id="brandTitleEn" name="title_en" class="form-control" type="text">
                            </div>
                            @error('title_en')
                                <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label w-100" for="brandSlug">@lang('brand::brands.slug')</label>
                            <div class="input-group input-group-merge">
                                <input id="brandSlug" name="slug" class="form-control" type="text">
                            </div>
                            @error('slug')
                            <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label w-100" for="brandImage">@lang('brand::brands.image')</label>
                            <div class="input-group input-group-merge">
                                <input id="brandImage" name="image" class="form-control" type="file">
                            </div>
                            @error('image')
                            <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                            @enderror
                        </div>
                        <input type="hidden" value="brand" name="folder">
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                @lang('brand::brands.add brand')
                            </button>
                            <a href="{{ route('brands.index') }}" type="reset" class="btn btn-label-secondary btn-reset">
                                @lang('dashboard::common.cancel')
                            </a>
                        </div>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection
