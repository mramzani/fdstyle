@extends('dashboard::layouts.master')
@section('dashboardTitle',__('category::categories.edit category'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/typography.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/editor.css')}}"/>
    <script src="{{ asset('assets/panel/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/quill/quill.js') }}"></script>
@endsection
@section('content')
    <!-- Start Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <x-dashboard::breadcrumb :breadcrumb-name="__('category::categories.edit category')"></x-dashboard::breadcrumb>
        <div class="row">
            @can('categories_edit')
                <form action="{{ route('categories.update',$category) }}" id="categoryUpdateForm" method="post"
                      class="row g-3"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col-12 col-md-8 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12 mb-2">
                                    <label class="form-label w-100"
                                           for="title_fa">@lang('dashboard::common.title_fa')</label>
                                    <div class="input-group input-group-merge">
                                        <input id="title_fa" name="title_fa" value="{{ $category->title_fa }}"
                                               class="form-control"
                                               placeholder="@lang('dashboard::common.title_fa')"
                                               type="text">
                                    </div>
                                    @error('title_fa')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label w-100"
                                           for="title_en">@lang('dashboard::common.title_en')</label>
                                    <div class="input-group input-group-merge">
                                        <input id="title_en" name="title_en" value="{{ $category->title_en }}"
                                               class="form-control"
                                               placeholder="@lang('dashboard::common.title_en')"
                                               type="text">
                                    </div>
                                    @error('title_en')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label w-100"
                                           for="categorySlug">@lang('category::categories.category slug')</label>
                                    <div class="input-group input-group-merge">
                                        <input id="categorySlug" name="slug" value="{{ $category->slug }}"
                                               class="form-control"
                                               placeholder="@lang('category::categories.category slug')"
                                               type="text">
                                    </div>
                                    @error('slug')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label w-100" for="categoryParent">
                                        @lang('category::categories.category parent')</label>
                                    <div class="input-group input-group-merge">
                                        <select id="categoryParent" name="parent_id" class="form-control">
                                            <option value="">@lang('category::categories.original category')</option>
                                            @include('category::category.common.category-select',['categories'=> $categories,'level' => 0])
                                        </select>
                                    </div>
                                    @error('parent_id')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label w-100" for="categoryImage">
                                        @lang('category::categories.category image')</label>
                                    <div class="input-group input-group-merge">
                                        <input id="categoryImage" name="image" class="form-control" type="file">
                                        <input name="folder" type="hidden" value="category">
                                    </div>

                                    @if($category->image)
                                        <div class="mt-3 d-flex justify-content-start align-items-start">
                                            <div class="avatar avatar-xl">
                                                <img class="rounded" id="currentImg" alt="current image"
                                                     src="{{ $category->image_url }}">
                                                <input type="hidden" name="old_image" value="{{ $category->image }}">
                                            </div>
                                            <button type="button" id="deleteCurrentImgBtn"
                                                    class="btn btn-outline-danger btn-sm m-2">
                                                @lang('dashboard::common.delete')
                                            </button>

                                        </div>
                                        @error('image')
                                        <x-dashboard::validation-error
                                            message="{{ $message }}"></x-dashboard::validation-error>
                                        @enderror
                                    @endif

                                </div>
                                <div class="divider">
                                    <div class="divider-text">سئو و توضیحات</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label w-100"
                                           for="seo_title">عنوان سئو دسته‌بندی</label>
                                    <div class="input-group input-group-merge">
                                        <input id="seo_title" name="seo_title" value="{{ $category->seo_title }}"
                                               class="form-control"
                                               placeholder="عنوان سئو دسته‌بندی"
                                               type="text">
                                    </div>
                                    @error('seo_title')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label w-100"
                                           for="seo_description">توضیحات سئو دسته‌بندی</label>
                                    <div class="input-group input-group-merge">
                                <textarea name="seo_description" class="form-control" id="seo_description"
                                          cols="30" rows="2">{{ $category->seo_description }}</textarea>
                                    </div>
                                    @error('seo_description')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label w-100"
                                           for="description">توضیحات دسته‌بندی</label>
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
                                    <div id="descriptionQuill">{!! $category->description !!}</div>
                                    <textarea name="description" class="d-none" id="description"
                                              cols="30" rows="2"></textarea>
                                    @error('description')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12">
                                    <label class="form-label w-100"
                                           for="variant">تنوع مجاز دسته‌بندی را انتخاب نمایید:</label>
                                    <div class="form-group">
                                        <select name="variant" class="form-control" id="variant">
                                            <option value="" selected disabled>انتخاب کنید</option>
                                            @foreach(\Modules\Product\Entities\Variant::pluck('id','title') as $title => $id)
                                                <option value="{{ $id }}"
                                                        @if($id == $category->variant_id) selected @endif>{{ $title }}</option>
                                            @endforeach
                                        </select>
                                        @error('variant')
                                        <x-dashboard::validation-error
                                            message="{{ $message }}"></x-dashboard::validation-error>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label w-100"
                                           for="variant">گروه ویژگی این دسته‌بندی را انتخاب نمایید:</label>
                                    <div class="form-group">
                                        <select name="attribute_group_id" class="form-control" id="attribute_group_id">
                                            <option value="" selected disabled>انتخاب کنید</option>
                                            @foreach(\Modules\Product\Entities\AttributeGroup::pluck('id','title') as $title => $id)
                                                <option value="{{ $id }}"
                                                        @if($id == $category->attribute_group_id) selected @endif>{{ $title }}</option>
                                            @endforeach
                                        </select>
                                        @error('attribute_group_id')
                                        <x-dashboard::validation-error
                                            message="{{ $message }}"></x-dashboard::validation-error>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label w-100"
                                           for="merchant_commission">@lang('dashboard::common.enter merchant commission value for this category')</label>
                                    <div class="input-group input-group-merge">
                                        <input id="merchant_commission" name="merchant_commission"
                                               value="{{ $category->merchant_commission }}"
                                               @if(!auth('admin')->user()->roles()->whereIn('name',['technicalAdmin'])->exists()) disabled
                                               @endif
                                               class="form-control"
                                               placeholder="@lang('dashboard::common.merchant_commission')"
                                               type="text">
                                    </div>
                                    <small>یک عدد از ۱ تا 99 وارد نمایید.</small>
                                    @error('merchant_commission')
                                    <x-dashboard::validation-error
                                        message="{{ $message }}"></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">
                                        @lang('dashboard::common.submit')
                                    </button>
                                    <a href="{{ route('categories.index') }}" type="reset"
                                       class="btn btn-label-secondary btn-reset">
                                        @lang('dashboard::common.cancel')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endcan
        </div>
    </div>
    <!-- End Content -->
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
        $("#categoryUpdateForm").click(function () {
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
