@extends('dashboard::layouts.master')
@section('dashboardTitle',__('category::categories.add category'))

@section('content')
    <!-- Start Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="__('category::categories.add category')"></x-dashboard::breadcrumb>
        <div class="card mb-4">
            <div class="card-body">
                @can('categories_create')
                <form action="{{ route('categories.store') }}" method="post" class="row g-3"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 mb-2">
                        <label class="form-label w-100"
                               for="title_fa">@lang('dashboard::common.title_fa')</label>
                        <div class="input-group input-group-merge">
                            <input id="title_fa" name="title_fa" class="form-control" type="text" placeholder="@lang('dashboard::common.title_fa')">
                        </div>
                        @error('title_fa')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label w-100"
                               for="title_en">@lang('dashboard::common.title_en')</label>
                        <div class="input-group input-group-merge">
                            <input id="title_en" name="title_en" class="form-control" type="text" placeholder="@lang('dashboard::common.title_en')">
                        </div>
                        @error('title_en')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label w-100"
                               for="categorySlug">@lang('category::categories.category slug')</label>
                        <div class="input-group input-group-merge">
                            <input id="categorySlug" name="slug" class="form-control" type="text" placeholder="@lang('category::categories.category slug')">
                        </div>
                        @error('slug')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>

                    <div class="col-12 mb-2">
                        <label class="form-label w-100" for="categoryParent">
                            @lang('category::categories.category parent')</label>
                        <div class="input-group input-group-merge">
                            <select id="categoryParent" name="parent_id" class="form-control">
                                <option value="">@lang('product::products.Select a product category')</option>
                                @include('category::category.common.category-select',['categories'=> $categories,'level' => 0])
                            </select>
                        </div>
                        @error('parent_id')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label w-100" for="categoryImage">
                            @lang('category::categories.category image')</label>
                        <div class="input-group input-group-merge">
                            <input id="categoryImage" name="image" class="form-control" type="file">
                        </div>
                        @error('image')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <input type="hidden" value="category" name="folder">
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            @lang('dashboard::common.submit')
                        </button>
                        <a href="{{ route('categories.index') }}" type="reset"
                           class="btn btn-label-secondary btn-reset">
                            @lang('dashboard::common.cancel')
                        </a>
                    </div>
                </form>
                @endcan
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection
