@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('product::attributes.manage attributes'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- add attribute to category section -->
        @can('add_variant_to_category')
            <div class="row justify-content-center">
                <div class="m-2">
                    @include('dashboard::partials.alert')
                </div>
                <div class="col-lg-12">
                    <div class="card my-2">
                        <div class="card-header bg-primary text-white py-2">افزودن تنوع به دسته‌بندی</div>
                        <div class="card-body my-2">
                            <form method="POST" action="{{ route('variants.attach.category') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-lg-3">
                                        <label for="category" class="form-label">دسته‌بندی محصولات</label>
                                        <select class="form-select" name="category" id="product_category">
                                            <option
                                                value="">@lang('product::attributes.Select a attributes category')</option>
                                            @include('category::category.common.category-select',
                                                ['categories' => \Modules\Category\Entities\Category::nested()->get(),'level' => 0])
                                        </select>

                                        @error('category')
                                        @include('dashboard::partials.validation-error')
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <label for="attribute" class="form-label">انتخاب تنوع</label>
                                        <select name="variant" class="form-control" id="attribute">
                                            <option value="">انتخاب کنید</option>
                                            @foreach(\Modules\Product\Entities\Variant::all()->pluck('title','id') as $id => $attribute)
                                                <option value="{{ $id }}">{{ $attribute }}</option>
                                            @endforeach
                                        </select>
                                        @error('variant')
                                        @include('dashboard::partials.validation-error')
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-3 mt-4">
                                        <button type="submit" class="btn btn-secondary">ذخیره</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        <!-- allowed attribute and category table section -->
        @can('view_categories_variants')
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card my-2">
                        <div class="card-header bg-secondary text-white py-2">تنوع و دسته‌بندی‌های مجاز</div>
                        <div class="card-body">
                            <table class="table border-top table-responsive">
                                <thead>
                                <tr>
                                    <th class="w-25">دسته‌بندی</th>
                                    <th class="w-25">تنوع</th>
                                    <th class="w-50">@lang('dashboard::common.operation')</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td><span class="badge bg-label-warning">{{ $category->title_fa ?? '' }}</span></td>
                                        <td><span
                                                class="badge bg-label-success">{{ $category->variant->title ?? '' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                @can('empty_variant_from_category')
                                                    <form action="{{ route('variants.empty.category',$category->id) }}"
                                                          id="deleteAttributeConfirm-{{ $category->id }}"
                                                          method="post" class="btn-group">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-icon
                                                        delete-attribute"
                                                                data-id="{{ $category->id }}"><i
                                                                class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        <!-- add value for attribute and category section -->
        @can('add_value_to_variant')
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card my-2">
                        <div class="card-header bg-primary text-white py-2">افزودن تنوع و مقدار برای دسته‌بندی خاص</div>
                        <div class="card-body my-2">
                            <form method="POST" action="{{ route('variant-values.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-lg-3">
                                        <label for="category" class="form-label">دسته‌بندی محصولات</label>
                                        <select class="form-select" name="category_id" id="category">
                                            <option
                                                value="">@lang('product::attributes.Select a attributes category')</option>
                                            @include('category::category.common.category-select',
                                                ['categories' => \Modules\Category\Entities\Category::nested()->get(),'level' => 0])
                                        </select>

                                        @error('category_id')
                                        @include('dashboard::partials.validation-error')
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <label for="attribute" class="form-label">انتخاب تنوع</label>
                                        <select name="variant_type" class="form-control" id="attribute"
                                                onchange="changeAttributeType(event)">
                                            <option value="">انتخاب کنید</option>
                                            @foreach(\Modules\Product\Entities\Variant::all()->pluck('title','type') as $type => $attribute)
                                                <option value="{{ $type }}">{{ $attribute }}</option>
                                            @endforeach
                                        </select>
                                        @error('variant_type')
                                        @include('dashboard::partials.validation-error')
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <label for="value" class="form-label">مقدار تنوع</label>
                                        <select name="value[]" class="form-control value" id="value" multiple>
                                        </select>
                                        @error('value')
                                        @include('dashboard::partials.validation-error')
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <div class="form-check mt-4">
                                            <label for="selectAll" class="form-check-label">انتخاب‌همه</label>
                                            <input type="checkbox" class="form-check-input" id="selectAll" name="selectAll">

                                        </div>


                                    </div>
                                    <div class="col-12 col-lg-1 mt-4">
                                        <button type="submit" class="btn btn-secondary">ذخیره</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        <!-- allowed value and attribute and category table section -->
        @can('view_values_variants')
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card my-2">
                        <div class="card-header bg-secondary text-white py-2">لیست مقادیر ویژگی‌ها</div>
                        <div class="card-body">
                            <div class="card-datatable table-responsive">

                                <table class="table border-top table-responsive">
                                    <thead>
                                    <tr>
                                        <th class="w-25">تنوع</th>
                                        <th class="w-25">دسته‌بندی</th>
                                        <th class="w-25">مقدار</th>
                                        <th class="w-25">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($variantValue as $value)
                                        <tr>
                                            <td>{{ $value->variant->title ?? '' }}</td>
                                            <td>{{ $value->category->title_fa ?? '' }}</td>
                                            <td>{{ $value->valuable->title ?? '' }}</td>
                                            <td>
                                                <div class="d-inline-block text-nowrap">
                                                    <a href="#"
                                                       class="btn btn-sm btn-icon d-none">
                                                        <i class="bx bx-edit"></i></a>
                                                    @can('delete_value_from_variant')
                                                        <form action="{{ route('variant-values.destroy',$value) }}"
                                                              id="deleteAttributeConfirm-{{ $value->id }}"
                                                              method="post" class="btn-group">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-icon
                                                        delete-attribute"
                                                                    data-id="{{ $value->id }}"><i
                                                                    class="bx bx-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center my-1">
                                    {{ $variantValue->links() }}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script type="text/javascript">
        $(".delete-attribute").on('click', function (event) {
            event.preventDefault();
            const btn = $(this);
            let id = $(this).data("id");

            Swal.fire({
                title: "@lang('dashboard::common.Are you sure to delete?')",
                text: "@lang('dashboard::common.If you delete the information, it will be lost!')",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "@lang('dashboard::common.confirm')",
                cancelButtonText: "@lang('dashboard::common.cancel')",
                buttonsStyling: false,
            }).then(function (value) {
                if (!value.dismiss) {
                    $("#deleteAttributeConfirm-" + id).submit();
                }
            });
        });

        $('.add_product_attribute').on('click', function () {
            let AttSection = $('#attribute_section');
            let CategorySelect = $("select[name='category_id']");
            let AttributeSelect = $("select[name='variant_type']");
            let ValueSelect = $("select[name='value']");

            let id = AttSection.children().length;
            let category = CategorySelect.find('option:selected').text();
            let category_id = CategorySelect.find('option:selected').val();

            let attribute = AttributeSelect.find('option:selected').text();
            let variant_type = AttributeSelect.find('option:selected').val();

            let value = ValueSelect.find('option:selected').text();
            let value_id = ValueSelect.find('option:selected').val();
            console.log(value, value_id);


            AttSection.append(
                createNewAtt({
                    id,
                    category,
                    category_id,
                    attribute,
                    variant_type,
                    value,
                    value_id,
                })
            );
        });


        let createNewAtt = ({id, category, category_id, attribute, variant_type, value, value_id}) => {
            return `
                <div class="row my-2 attribute-row " id="attribute-${id}">
                    <div class="col-3">
                        <input class="form-control form-control-sm" value="${category}" readonly name="attributes[${id}][category]" type="text">
                        <input type="hidden" name="attributes[${id}][category_id]" value="${category_id}">
                    </div>
                    <div class="col-3">
                        <input class="form-control form-control-sm" value="${attribute}" readonly name="attributes[${id}][attribute]" type="text">
                        <input type="hidden" name="attributes[${id}][variant_type]" value="${variant_type}">
                    </div>
                    <div class="col-3">
                        <input class="form-control form-control-sm" value="${value}" readonly name="attributes[${id}][value]" type="text">
                        <input type="hidden" name="attributes[${id}][value_id]" value="${value_id}">
                    </div>
                    <div class="col-1 remove">
                        <div class=""><i class='bx bx-trash'></i></div>
                    </div>
                </div>
                `
        }

        $(".value").select2({
            templateResult: optionSelect,
        });

        function optionSelect(opt) {
            if (!opt.id) {
                return opt.text.toUpperCase();
            }
            var colorCode = $(opt.element).attr('data-color-code');

            if (!colorCode) {
                return opt.text.toUpperCase();
            } else {
                var $opt = $(
                    '<span>' +
                    '<div class="badge badge-dot" style="height:15px;width:15px;background-color:' + colorCode + '">' +
                    '</div>' +
                    opt.text.toUpperCase() +
                    '</span>');
                return $opt;
            }

        }

        $("#category").select2();
        $("#product_category").select2();

        $("#selectAll").on("click",function(){
            if($("#selectAll").prop('checked') === true){
                $("select#value > option").prop("selected",true);
                $("select#value").trigger("change");
            } else {
                $("select#value > option").prop('selected', false);
                $("select#value").trigger("change");
            }
        });


        $(document).on('click', '.remove', function () {
            $(this).parents('.attribute-row').remove();
        });


        let changeAttributeType = (event) => {
            let valueBox = $(`select[name='value[]']`);
            var variant = event.target.value;

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('attributes.get-categories') }}',
                contentType: "application/json;charset=utf-8",
                data: JSON.stringify({
                    type: event.target.value
                }),
                beforeSend: function () {
                    blockUi();
                },
                complete: function () {
                    $(".content-wrapper").unblock();
                },
                success: function (res) {
                    valueBox.html(`
                    <option value="" disabled>انتخاب کنید</option>
                        ${
                        res.values.map(function (value) {
                            return `<option value="${value['id']}" ${ variant === 'color' ? "data-color-code=" + '#' + value['code'] : ''}>
                                        ${value['title']}
                                    </option>`
                        })
                    }
                    `);
                }
            });

        }

    </script>
@endsection
