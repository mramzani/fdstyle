@extends('dashboard::layouts.master')
@section('dashboardTitle',__('brand::brands.edit brand'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/typography.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/editor.css')}}"/>
    <script src="{{ asset('assets/panel/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/quill/quill.js') }}"></script>
@endsection
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="__('brand::brands.edit brand')"></x-dashboard::breadcrumb>
        <div class="card mb-4">
            <div class="card-body">
                @can('brands_edit')
                <form action="{{ route('brands.update',$brand) }}" method="post" id="brandUpdateForm" class="row g-3"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-12 mb-2">
                        <label class="form-label w-100" for="brandName">@lang('brand::brands.title_fa')</label>
                        <div class="input-group input-group-merge">
                            <input id="brandName" name="title_fa" value="{{ $brand->title_fa }}" class="form-control" type="text">
                        </div>
                        @error('title_fa')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label w-100" for="title_en">@lang('brand::brands.title_en')</label>
                        <div class="input-group input-group-merge">
                            <input id="title_en" name="title_en" value="{{ $brand->title_en }}" class="form-control" type="text">
                        </div>
                        @error('title_en')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label w-100" for="brandSlug">@lang('brand::brands.slug')</label>
                        <div class="input-group input-group-merge">
                            <input id="brandSlug" name="slug" value="{{ $brand->slug }}" class="form-control" type="text">
                        </div>
                        @error('slug')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label w-100" for="brandImage">@lang('brand::brands.image')</label>
                        <div class="input-group input-group-merge">
                            <input id="brandImage" name="image" class="form-control" type="file">
                            <input name="folder" type="hidden" value="brand">
                        </div>

                        @if($brand->image)
                            <div class="mt-3 d-flex justify-content-start align-items-start">
                                <div class="avatar avatar-xl">
                                    <img class="rounded" id="currentImg" alt="current image"
                                         src="{{ $brand->image_url }}">
                                    <input type="hidden" name="old_image" value="{{ $brand->image }}">
                                </div>
                                <button type="button" id="deleteCurrentImgBtn"
                                        class="btn btn-outline-danger btn-sm m-2">
                                    @lang('dashboard::common.delete')
                                </button>

                            </div>
                            @error('image')
                            <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                            @enderror
                        @endif
                    </div>
                    <div class="divider">
                        <div class="divider-text">سئو و توضیحات</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label w-100"
                               for="seo_title">عنوان سئو برند</label>
                        <div class="input-group input-group-merge">
                            <input id="seo_title" name="seo_title" value="{{ $brand->seo_title }}"
                                   class="form-control"
                                   placeholder="عنوان سئو برند"
                                   type="text">
                        </div>
                        @error('seo_title')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label w-100"
                               for="seo_description">توضیحات سئو برند</label>
                        <div class="input-group input-group-merge">
                            <textarea name="seo_description" class="form-control" id="seo_description"
                                      cols="30" rows="2">{{ $brand->seo_description }}</textarea>
                        </div>
                        @error('seo_description')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label w-100"
                               for="description">توضیحات برند</label>
                        <div id="toolbarQuill">
                            <span class="ql-formats">
                                <select class="ql-font"></select>
                                <select class="ql-size"></select>
                              </span>
                            <span class="ql-formats">
                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>
                                <button class="ql-underline"></button>
                                <button class="ql-strike"></button>
                              </span>
                            <span class="ql-formats">
                                <select class="ql-color"></select>
                                <select class="ql-background"></select>
                              </span>
                            <span class="ql-formats">
                                <button class="ql-link"></button>
                              </span>

                            <span class="ql-formats">
                                <button class="ql-header" value="1"></button>
                                <button class="ql-header" value="2"></button>
                                <button class="ql-header" value="3"
                                        style="display: flex; place-items: center;">H3</button>
                                <button class="ql-header" value="4"
                                        style="display: flex; place-items: center;">H4</button>
                                <button class="ql-header" value="5"
                                        style="display: flex; place-items: center;">H5</button>
                                <button class="ql-header" value="6"
                                        style="display: flex; place-items: center;">H6</button>
                              </span>
                        </div>
                        <div id="descriptionQuill">{!! $brand->description !!}</div>
                        <textarea name="description" class="d-none" id="description"
                                  cols="30" rows="2"></textarea>
                        @error('description')
                        <x-dashboard::validation-error message="{{ $message }}"></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            @lang('dashboard::common.update')
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
@section('script')
    <script type="text/javascript">
        const descriptionQuill = new Quill('#descriptionQuill', {
            bounds: '#descriptionQuill',
            placeholder: null,
            modules: {
                toolbar: '#toolbarQuill'
            },
            theme: 'snow'
        });
        $("#brandUpdateForm").click(function () {
            let description = $("textarea[name='description']");
            description.html(descriptionQuill.root.innerHTML);
        });
        $('#deleteCurrentImgBtn').on('click', function () {
            $('input[name="old_image"]').remove();
            $("#currentImg").remove();
            this.remove();
        });
    </script>
@endsection
