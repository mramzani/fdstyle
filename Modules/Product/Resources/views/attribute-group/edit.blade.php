@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش گروه ویژگی‌ ' . $attribute_group->title)
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="col-lg-12">
                <div class="card my-2">
                    <div class="card-header bg-primary text-white py-2">{{ 'ویرایش ' . $attribute_group->title }}</div>
                    <div class="card-body my-2">
                        <form method="POST" action="{{ route('attribute-group.update',$attribute_group->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12 col-lg-3">
                                    <label for="title" class="form-label">عنوان گروه ویژگی</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                           value="{{ $attribute_group->title }}">
                                    @error('title')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                                {{--<div class="col-12 col-lg-3">
                                    <label for="category" class="form-label">دسته‌بندی گروه ویژگی</label>
                                    <select class="form-select" name="category_id" id="category">
                                        <option value="">@lang('product::products.Select a product category')</option>
                                        @include('category::category.common.category-select',
                                            ['categories' => \Modules\Category\Entities\Category::nested()->get(),'level' => 0])
                                    </select>
                                    @error('category')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>--}}
                                <div class="col-12 col-lg-3 mt-4">
                                    <button type="submit" class="btn btn-secondary">بروزرسانی عنوان گروه</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="card-body my-2">
                            <form method="POST"
                                  action="{{ route('attribute-group.add-attribute',$attribute_group->id) }}">
                                @csrf
                                <div>
                                    <h6>ویژگی‌های گروه {{ $attribute_group->title }}</h6>
                                    <hr>
                                    <div id="attributes"
                                         data-attributes="{{ json_encode(\Modules\Product\Entities\Attribute::all()->pluck('name')) }}"></div>
                                    <div id="attribute_section" class="my-2">
                                        @foreach($attribute_group->attributes as $attribute)
                                            <div class="row d-flex" id="attribute-{{ $loop->index }}">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="attribute_title">عنوان
                                                            ویژگی</label>
                                                        <select id="attribute_title"
                                                                name="attributes[{{ $loop->index }}][name]"
                                                                class="attribute-select form-control">
                                                            @foreach($attribute_group->attributes as $attr)
                                                                <option value="{{ $attr->name }}"
                                                                        @if($attr->name == $attribute->name) selected @endif>{{ $attr->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-4 mt-3 d-inline-block">
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="document.getElementById('attribute-{{ $loop->index }}').remove()">
                                                        حذف
                                                    </button>
                                                    <div class="form-check form-check-inline mt-3 mx-2">
                                                        <input class="form-check-input" type="checkbox"
                                                               @if($attribute->is_filterable) checked @endif
                                                               name="attributes[{{ $loop->index }}][filterable]"
                                                               id="filterable-{{ $loop->index }}"
                                                               value="filterable">
                                                        <label class="form-check-label"
                                                               for="filterable-{{ $loop->index }}">قابل
                                                            فیلتر</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="btn btn-sm btn-danger" type="button" id="add_product_attribute">ویژگی
                                        جدید
                                    </button>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-info">ذخیره</button>
                                    <a href="{{ route('attribute-group.index') }}" class="btn btn-primary float-left">لغو</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script type="text/javascript">
        $("#category").select2();
        let createNewAttr = ({attributes, id}) => {
            return `<div class="row" id="attribute-${id}">
                        <div class="col-4">
                            <div class="form-group">
                                   <label class="form-label">عنوان ویژگی</label>
                                 <select name="attributes[${id}][name]" class="attribute-select form-control">
                                    <option selected disabled>انتخاب کنید</option>
                                    ${
                                        attributes.map(function (item) {
                                            return `<option value="${item}">${item}</option>`
                                        })
                                    }
                                 </select>
                            </div>
                        </div>
                        <div class="col-4 mt-3 d-inline-block">
                            <button type="button" class="btn btn-sm btn-warning" onclick="document.getElementById('attribute-${id}').remove()">حذف</button>
                                <div class="form-check form-check-inline mt-3 mx-2">
                                    <input class="form-check-input" type="checkbox" name="attributes[${id}][filterable]" id="filterable_${id}"
                                        value="filterable">
                                    <label class="form-check-label" for="filterable_${id}">قابل فیلتر</label>
                                </div>
                        </div>
                    </div>`
        }

        $('#add_product_attribute').click(function () {
            let attributesSection = $('#attribute_section');
            let id = attributesSection.children().length;
            let attributes = $("#attributes").data('attributes');

            attributesSection.append(
                createNewAttr({
                    attributes,
                    id
                })
            );
            $('.attribute-select').select2({tags: true});
        });

        $('.attribute-select').select2({tags: true});
    </script>
@endsection
