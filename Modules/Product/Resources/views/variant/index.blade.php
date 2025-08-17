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
                                                    <span
                                                        class="text-gray">{{ $product->category->title_fa ?? 'نامشخص' }}</span>
                                                </div>
                                                <div class="d-table-cell" style="padding: 5px 0 5px 100px;">
                                                    <span>تنوع مجاز این کالا: </span>
                                                    <span
                                                        class="text-gray">{{ $product->category->variant->title ?? 'بدون رنگ یا سایز' }}</span>
                                                </div>
                                            </li>
                                            <li class="d-table-row">
                                                <div class="d-table-cell" style="padding: 5px 0 5px 100px;">
                                                    <span>ابعاد بسته‌بندی محصول (ارتفاع×عرض×طول):</span>
                                                    <span
                                                        class="text-gray">{{ $product->detail->height . '×' . $product->detail->width . '×' . $product->detail->length  }}</span>
                                                </div>
                                                <div class="d-table-cell" style="padding: 5px 0 5px 100px;">
                                                    <span> وزن بسته‌بندی محصول:</span>
                                                    <span class="text-gray">{{ $product->detail->weight }} گرم </span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @can('create_product_variants')
                            <form method="POST" action="{{ route('products.variants.store',$product) }}">
                                @csrf
                                <input type="hidden" name="productVariant[variant]"
                                       value="{{ $product->category->variant->type ?? 'no_color_no_size' }}">

                                @if($product->hasVariantsProduct())
                                    <div class="card shadow-none bg-transparent border border-primary my-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label for="variant_value"
                                                           class="form-label">{{ $product->category->variant->title }}</label>
                                                    <select name="productVariant[value]" class="form-control"
                                                            id="variant_value">
                                                        <option value="">انتخاب کنید</option>
                                                        @foreach($product->category->variant->allowedValuesForVariant($product) as $key => $item)
                                                            <option
                                                                value="{{ $item->valuable->id }}">{{ $item->valuable->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('productVariant.value')
                                                    <x-dashboard::validation-error
                                                        message="{{ $message }}"></x-dashboard::validation-error>
                                                    @enderror
                                                </div>
                                                <div class="col-3">
                                                    <label for="purchase_price"
                                                           class="form-label">@lang('product::products.purchase price')</label>
                                                    <input type="text" id="purchase_price" class="form-control" value="{{ $product->detail->purchase_price }}"
                                                           dir="ltr" name="purchase_price">
                                                    @error('purchase_price')
                                                    <x-dashboard::validation-error
                                                        message="{{ $message }}"></x-dashboard::validation-error>
                                                    @enderror
                                                </div>
                                                <div class="col-3">
                                                    <label for="sales_price"
                                                           class="form-label">@lang('product::products.sales price')</label>
                                                    <input type="text" id="sales_price" class="form-control" dir="ltr" value="{{ $product->detail->sales_price }}"
                                                           name="sales_price">
                                                    @error('sales_price')
                                                    <x-dashboard::validation-error
                                                        message="{{ $message }}"></x-dashboard::validation-error>
                                                    @enderror
                                                </div>
                                                <div class="col-3">
                                                    <label for="code" class="form-label">کد نگه‌داری انبار</label>
                                                    <input type="text" class="form-control" name="code" dir="ltr"
                                                           placeholder="کد تنوع" id="code" readonly>


                                                    @error('code')
                                                    <x-dashboard::validation-error
                                                        message="{{ $message }}"></x-dashboard::validation-error>
                                                    @enderror
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-4">
                                        <button type="submit" class="btn btn-secondary" id="name">ذخیره</button>
                                        <a href="{{ route('products.edit',$product) }}" class="btn btn-outline-primary">بازگشت
                                            به
                                            ویرایش محصول</a>
                                    </div>

                                @endif
                                @endcan
                                @can('view_product_variants')
                                    @if($product->hasVariantsProduct())
                                        <div class="col-lg-12">
                                            <table class="table border-top table-responsive my-2">
                                                <thead>
                                                <tr>
                                                    <th class="w-25">{{ $product->category->variant->title ?? 'بدون رنگ یا سایز' }}</th>
                                                    <th class="w-25">@lang('product::products.purchase price')</th>
                                                    <th class="w-25">@lang('product::products.sales price')</th>
                                                    <th class="w-25">@lang('product::products.sku')</th>
                                                    <th class="w-25">@lang('dashboard::common.operation')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($product->getProductVariant() as $productVariant)
                                                    <tr id="{{ $productVariant->id }}">
                                                        <td>{{ $productVariant->option->valuable->title }}</td>
                                                        <td>{{ $productVariant->purchase_price ?? '' }}</td>
                                                        <td>{{ $productVariant->sales_price ?? '' }}</td>
                                                        <td dir="ltr">{{ $productVariant->code ?? '' }}</td>
                                                        <td>
                                                            <div class="d-inline-block text-nowrap">
                                                                <form action="#"></form>
                                                                @can('delete_product_variants')
                                                                    <form
                                                                        action="{{ route('products.variants.destroy',$productVariant) }}"
                                                                        id="deleteProductVariantConfirm-{{ $productVariant->id }}"
                                                                        method="post" class="btn-group">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="btn btn-sm btn-icon delete-variant"
                                                                                data-id="{{ $productVariant->id }}"><i
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
                                    @else
                                        <div class="alert alert-info m-2">
                                            <span>برای این محصول تنوع مجاز درج نشده است.</span>
                                            <a href="{{ route('products.edit',$product) }}"
                                               class="btn btn-secondary btn-sm">ویرایش محصول</a>
                                        </div>
                                    @endif
                                @endcan

                            </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script type="text/javascript">

        $("#variant_value").select2();

        $('#variant_value').on('change', function () {

            let valueSelected = $(this).find('option:selected').text();
            let productCode = "{{ $product->barcode }}";
            let variation_option = $('input[name="productVariant[variant]"]').val();
            if ($(this).find('option:selected').val().length === 0) {
                $('#code').val('');
            } else if (variation_option === 'size') {
                $('#code').val(productCode + '-' + valueSelected);
            } else {
                let valSelect = $(this).find('option:selected').val();
                $.get("{{ route('get-color-code') }}", {id: valSelect}, function (data) {
                    console.log(data);
                    $('#code').val(productCode + '-' + data);
                });
            }

        });

        $(".delete-variant").on('click', function (event) {
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
                if (value.isConfirmed) {
                    $("#deleteProductVariantConfirm-" + id).submit();
                }
            });
        });

    </script>
@endsection
