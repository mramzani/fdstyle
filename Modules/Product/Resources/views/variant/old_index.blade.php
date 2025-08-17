@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('product::variations.manage product variants'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card my-2">
                    <div
                        class="card-header bg-primary text-white py-2">@lang('product::variations.manage product variants')</div>
                    <div class="card-body my-2">
                        <div class="my-1">
                            @include('dashboard::partials.alert')
                        </div>
                        <form method="POST" action="{{ route('products.variants.store',$product) }}" id="choice_form">
                            @csrf
                            <input type="hidden" name="variation[option]"
                                   value="{{ $product->category->attribute->title ?? 'no_color_no_size' }}">
                            <div class="row">
                                <div class="col-md-2 align-self-center align-content-center my-0 mx-2">
                                    <img src="{{ $product->image_url }}" alt="google home" class="w-100 h-auto">
                                </div>
                                <div class="col-md-8 d-flex flex-column justify-content-center">
                                    <h6>{{ $product->name }}</h6>
                                    <div class="card shadow-none bg-transparent border border-secondary mb-3">
                                        <div class="card-body">
                                            <ul class="d-table">
                                                <li class="d-table-row">
                                                    <div class="d-table-cell" style="padding: 5px 0 5px 100px;">
                                                        <span>دسته‌بندی:</span>
                                                        <span class="text-gray">{{ $product->category->title_fa ?? 'نامشخص' }}</span>
                                                    </div>
                                                    <div class="d-table-cell" style="padding: 5px 0 5px 100px;">
                                                        <span>تنوع مجاز این کالا: </span>
                                                        <span
                                                            class="text-gray">{{ $product->category->attribute->title ?? 'بدون رنگ یا سایز' }}</span>
                                                    </div>
                                                </li>
                                                <li class="d-table-row">
                                                    <div class="d-table-cell" style="padding: 5px 0 5px 100px;">
                                                        <span>ابعاد بسته‌بندی محصول (ارتفاع×عرض×طول):</span>
                                                        <span class="text-gray">0</span>
                                                    </div>
                                                    <div class="d-table-cell" style="padding: 5px 0 5px 100px;">
                                                        <span> وزن بسته‌بندی محصول:</span>
                                                        <span class="text-gray">{{ $product->detail->weight }}</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--@if($product->hasVariantsProduct())
                                <div class="card shadow-none bg-transparent border border-secondary my-2">
                                    <div class="card-body p-1">
                                        @foreach($product->category->attribute->allowedValuesForAttribute($product) as $key => $item)
                                            <button type="button" class="btn btn-sm btn-secondary js-add-variant"
                                                    data-attribute-value="{{ $item->valuable->id }}"
                                                    data-title="{{ $item->valuable->title }}">
                                            <span class="variant-btn-label">
                                                <span class="variant-btn-text">{{ $item->valuable->title }}</span>
                                                <span class="variant-btn-counter d-none">
                                                    (<span class="js-variant-count"></span>)
                                                </span>
                                            </span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif--}}
                            <div class="card shadow-none bg-transparent border border-primary my-2">
                                <div class="card-body">
                                    <div class="row">
                                        @if($product->hasVariantsProduct())
                                            <div class="col-3">
                                                <label class="form-label">{{ $product->category->attribute->title }}</label>
                                                <select name="variation[value]" class="form-control form-control-sm" id="">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach($product->category->attribute->allowedValuesForVariant($product) as $key => $item)
                                                        <option value="{{ $item->valuable->id }}">{{ $item->valuable->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                            <div class="col-3">
                                                <label for="purchase_price"
                                                       class="form-label">@lang('product::products.purchase price')</label>
                                                <input type="text" id="purchase_price" class="form-control form-control-sm" name="purchase_price">
                                            </div>
                                            <div class="col-3">
                                                <label for="sales_price" class="form-label">@lang('product::products.sales price')</label>
                                                <input type="text" id="sales_price" class="form-control form-control-sm" name="sales_price">
                                            </div>
                                            <div class="col-3">
                                                <label for="sku" class="form-label">کد نگه‌داری انبار</label>
                                                <input type="text" id="sku" class="form-control form-control-sm" name="sku">
                                            </div>
                                    </div>
                                    <div class="row my-1">

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <button type="submit" class="btn btn-secondary" id="name">ذخیره</button>
                                <a href="{{ route('products.edit',$product) }}" class="btn btn-outline-primary">بازگشت به
                                    ویرایش محصول</a>
                            </div>
                        </form>
                        {{--<div class="attribute_div" id="attribute_select_div">
                            <label for="choice_attributes" class="form-label">نام</label>
                            <select name="choice_attributes[]" class="form-control choice_attributes"
                                    id="choice_attributes">
                                <option value="">انتخاب کنید</option>
                                <option value="{{ $product->hasVariantsProduct()->id }}"> {{ $product->hasVariantsProduct()->title }}</option>
                               --}}{{-- @foreach($product->hasVariantsProduct() as $value)
                                    <option value="{{ $value->value }}">{{ $value->value }}</option>
                                @endforeach--}}{{--
                            </select>
                            @error('name')
                            @include('dashboard::partials.validation-error')
                            @enderror
                        </div>--}}
                        <div class="col-lg-12">
                            <div class="customer_choice_options d-none" id="customer_choice_options" style="">
                                @if($product->getVariations()->count() != 0 )
                                    @foreach($product->getVariations()->unique('attribute_id') as $key => $variant)
                                        <div class="row my-1 variant_row_lists">
                                            <div class="col-12 col-lg-4 my-1">
                                                <input type="hidden" name="choice_no[]"
                                                       id="attribute_id_{{ $variant->attribute_id }}"
                                                       value="{{ $variant->attribute_id }}">
                                                <div class="mb-25">
                                                    <input class="form-control"
                                                           width="40%" name="choice[]" type="text"
                                                           value="{{ \Modules\Product\Entities\Attribute::findOrFail($variant->attribute_id)->title }}"
                                                           readonly></div>
                                            </div>
                                            <div class="col-10 col-lg-7 my-1">
                                                <div class="mb-25">
                                                    <select name="choice_options_{{ $variant->attribute_id }}[]"
                                                            id="choice_options_{{ $variant->attribute_id }}"
                                                            class="form-control mb-15 choice_options"
                                                            multiple>
                                                        @foreach($variant->attribute->values()->where('category_id',$product->category->id)->get() as $key => $value)
                                                            <option value="{{ $value->id }}"
                                                                    @if($product->variations->where('value_id', $value->id)->first()) selected @endif>
                                                                {{ $value->valuable->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-2 col-lg-1">
                                                <a class="btn cursor-pointer attribute_remove" data-id="1"><i
                                                        class="bx bx-trash"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <table class="table border-top table-responsive table-border my-2">
                                <thead>
                                    <tr>
                                        <th class="w-25">{{ $product->category->attribute->title ?? 'بدون رنگ یا سایز' }}</th>
                                        <th class="w-25">@lang('product::products.purchase price')</th>
                                        <th class="w-25">@lang('product::products.sales price')</th>
                                        <th class="w-25">@lang('product::products.sku')</th>
                                        <th class="w-25">@lang('dashboard::common.operation')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->getVariations() as $variant)
                                    <tr>
                                        <td>{{ $variant->option->valuable->title }}</td>
                                        <td>{{ $variant->productSku->purchase_price ?? '' }}</td>
                                        <td>{{ $variant->productSku->sales_price ?? '' }}</td>
                                        <td dir="ltr">{{ $variant->product->barcode . '-' . $variant->productSku->sku ?? '' }}</td>
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                <a href="#"
                                                   class="btn btn-sm btn-icon d-none">
                                                    <i class="bx bx-edit"></i></a>

                                                <form action="#"
                                                      id="deleteAttributeConfirm-{{ $variant->id }}"
                                                      method="post" class="btn-group">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-icon
                                                        delete-attribute"
                                                            data-id="{{ $variant->id }}"><i
                                                            class="bx bx-trash"></i>
                                                    </button>
                                                </form>
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
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script type="text/javascript">


        //------------------------------------
        $('.js-add-variant').click(function () {
            let $this = $(this);
            let $productVariantTemplate = $('#productVariantTemplate > div').clone();

            if (isColorVariation()) {
                let $variantCountContainer = $this.find('.js-variant-count:first');
                let $variantCount = $variantCountContainer.text();

                if ($variantCount === '') {
                    $variantCountContainer.text(1);
                    $variantCountContainer.parent().removeClass('d-none');
                } else {
                    $variantCountContainer.text(parseInt($variantCount) + 1);
                }

                $this.addClass('border-info');


                let $attributeTitleContainer = $productVariantTemplate.find('.variant-attribute-title:first');
                $attributeTitleContainer.val($this.data('title'));
            }

            $('#variantsContainer').append($productVariantTemplate);

        });

        function isColorVariation() {
            let variation_theme = $("input[name='product_variants[variation_theme]']").val();
            if (variation_theme == 'color') return true;
        }

        //------------------------------------

        @if($product->getVariations()->count() != 0)
        @foreach($product->getVariations()->unique('attribute_id') as $key => $variant)
        $("#choice_options_" + {{ $variant->attribute_id }}).select2();
        @endforeach
        getCombinations(true);
        @endif


        $(".choice_options").select2();
        $("#choice_attributes").select2();

        $(document).on('change', '#choice_attributes', function () {
            var a_id = $(this).val();
            console.log(a_id);
            var exsist = $('#attribute_id_' + a_id).length;

            if (exsist > 0) {
                console.log('This item already added to list.');
                let attEl = $("#choice_attributes");
                attEl.val('');
                attEl.select2();
                return false;
            }

            getAttributeData(a_id);
        });

        function getAttributeData(a_id) {

            $.ajax({
                type: "POST",
                url: '{{ route('products.get-attribute-values',$product) }}',
                data: {id: a_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#customer_choice_options').append(data);
                    $('#choice_options_' + a_id).select2();
                    let choiceAttEl = $('#choice_attributes');
                    choiceAttEl.val('');
                    choiceAttEl.select2();
                },
                error: function (error) {
                    console.log(error);
                },
            });
        }

        function getCombinations(old = false) {
            let formdata = $('#choice_form').serializeArray();
            $.ajax({
                type: "POST",
                url: "",
                data: formdata,
                success: function (data) {
                    $('.combination').html(data);
                }
            });
        }

        $(document).on('change', '.choice_options', function (event) {
            getCombinations();
        });


        $(document).on('click', '.attribute_remove', function () {
            //$(this).parents('.variant_row_lists').remove();
            let this_data = $(this)[0];
            let row = this_data.parentNode.parentNode;

            //console.log(row.parentNode);
            row.parentNode.removeChild(row);
            //$('.customer_choice_options').html('');
            getCombinations(true);
        });
        $(document).on('click', '.variation_remove', function () {
            $(this).parents('.variant-tbl').remove();
        });


    </script>
@endsection
