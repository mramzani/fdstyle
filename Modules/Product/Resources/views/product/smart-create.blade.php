@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('product::products.add product'))
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/typography.css')}}"/>

    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/toastr/toastr.css') }}">
@endsection
@section('content')
    <!-- Start Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-start">
            <x-dashboard::breadcrumb :breadcrumb-name="__('product::products.add product')"></x-dashboard::breadcrumb>
            <div class="">
                <button type="submit" id="create-product-btn"
                        class="btn btn-primary create-product-btn">@lang('product::products.add product')</button>
            </div>
        </div>
        <form id="create-product-form" action="{{ route('product.store-smart-product') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-9 col-xl-9 col-sm-12">
                    <!-- product info -->
                    <div class="card mb-4">
                        <div
                            class="card-header bg-primary text-white py-2">@lang('product::products.Basic product specifications')</div>
                        <div class="card-body my-1 p-3">
                            <div class="my-3">
                                <input type="text" id="title" name="title"
                                       class="form-control text-start" value="{{ $product['name'] }}"
                                       placeholder="@lang('product::products.product name')*">
                                @error('title')
                                <x-dashboard::validation-error></x-dashboard::validation-error>
                                @enderror
                            </div>
                            <div class="my-3">
                                <input type="text" id="slug" class="form-control text-start"
                                       placeholder="به صورت خودکار ایجاد میشود" disabled>
                            </div>

                            <div class="input-group">
                                <input type="text" name="barcode" class="form-control text-start"
                                       placeholder="@lang('product::products.barcode')" id="barcode"
                                       aria-describedby="btn-generate-barcode">
                                <button class="btn btn-outline-primary" type="button" id="btn-generate-barcode">
                                    @lang('product::products.generate barcode')
                                </button>
                                @error('barcode')
                                <x-dashboard::validation-error></x-dashboard::validation-error>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- product price -->
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class=" col-md-6 col-sm-12 my-1 ">
                                    <input type="number" id="purchase_price" name="purchase_price"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.purchase price')">
                                    <small
                                        class="text-muted form-text">@lang('product::products.Customers will not see this')</small>

                                    @error('purchase_price')
                                    <x-dashboard::validation-error></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-sm-12 my-1">
                                    <input type="number" id="sales_price" name="sales_price"
                                           value="{{ $product['price'] }}"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.sales price')">
                                    @error('sales_price')
                                    <x-dashboard::validation-error></x-dashboard::validation-error>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- product category -->
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="mb-0 col-md-6 col-xl-6 col-sm-12">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <label for="category"
                                               class="form-label">@lang('dashboard::common.category')</label>
                                        <a href="{{ route('categories.index') }}" target="_blank"
                                           class="link-primary small">
                                            <span>@lang('dashboard::common.manage')</span>
                                        </a>
                                    </div>
                                    <select class="form-select" name="category_id" id="category">
                                        <option value="">@lang('product::products.Select a product category')</option>
                                        @include('category::category.common.category-select',
                                            ['categories' => \Modules\Category\Entities\Category::nested()->get(),'level' => 0])
                                    </select>
                                    @error('category_id')
                                    <x-dashboard::validation-error></x-dashboard::validation-error>
                                    @enderror
                                </div>
                                <div class="mb-0 col-md-6 col-xl-6 col-sm-12">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <label for="brand" class="form-label">@lang('dashboard::common.brand')</label>
                                        <a href="{{ route('brands.index') }}" target="_blank"
                                           class="link-primary small">
                                            <span>@lang('dashboard::common.manage')</span>
                                        </a>
                                    </div>
                                    <select class="form-select" name="brand_id" id="brand">
                                        <option value="">@lang('product::products.Select a product brand')</option>
                                        @foreach(\Modules\Brand\Entities\Brand::all()->pluck('title_fa','id') as $id => $brand)
                                            <option value="{{ $id }}">{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                    <x-dashboard::validation-error></x-dashboard::validation-error>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>


                    <!-- upload product image -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <img src="{{ $product['image_url'] }}" alt="" width="800" height="800">
                        </div>
                        <input type="hidden" id="folder" name="folder" value="product">
                        <input type="hidden" id="img_url" name="image" value="{{ $product['image_url'] }}">
                        @error('image')
                        <x-dashboard::validation-error></x-dashboard::validation-error>
                        @enderror
                    </div>
                    <!-- product attributes -->
                </div>
                <div class="col-md-3 col-xl-3 col-sm-12">
                    <div class="mb-4">
                        <button type="submit" class="btn btn-success d-grid w-100 mb-3 create-product-btn"
                                id="create-product-btn">
                                <span class="d-flex align-items-center justify-content-center text-nowrap">
                                  @lang('product::products.create product')
                                </span>
                        </button>

                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- End Content -->
@endsection

@section('script')
    <script src="{{ asset('assets/panel/vendor/js/dropzone-v5.9.3.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    {{--<script src="{{ asset('assets/panel/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>--}}
    <script src="{{ asset('assets/panel/vendor/js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        toastr.options = {
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "showDuration": "500",
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "3000",
            "preventDuplicates": true,
        }


        $("#category").select2({
            placeholder: "یک دسته‌بندی را انتخاب کنید",
        });
        $("#brand").select2({
            placeholder: "یک برند را انتخاب کنید",
        });
        $("#unit").select2({
            placeholder: "یک واحد‌شمارش را انتخاب کنید",
        });

        Dropzone.autoDiscover = false;

        const previewTemplate = `<div class="dz-preview dz-file-preview">
                <div class="dz-details">
                  <div class="dz-thumbnail">
                    <img data-dz-thumbnail>
                    <span class="dz-nopreview">بدون پیش‌نمایش</span>
                    <div class="dz-success-mark"></div>
                    <div class="dz-error-mark"></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                    <div class="progress">
                      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0"
                            aria-valuemax="100" data-dz-uploadprogress></div>
                    </div>
                  </div>
                  <div class="dz-filename" data-dz-name></div>
                  <div class="dz-size" data-dz-size></div>
                </div>
                  </div>`;

        const myDropzone = $("div#imgDropzone").dropzone({
            previewTemplate: previewTemplate,
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 100,
            maxFilesize: 12,
            paramName: 'image',
            clickable: true,
            method: "post",
            url: "{{ route('products.store') }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            init: function () {
                var formEl = "#create-product-form";
                var imgDropzone = this;
                //---------------------------
                $('.create-product-btn').on("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if ($(formEl).valid()) {
                        if (imgDropzone.getAcceptedFiles().length) {
                            imgDropzone.processQueue();
                        } else {
                            var description = $("textarea[name='description']");
                            description.html(quill.root.innerHTML);
                            $.ajax({
                                type: "POST",
                                url: '{{ route('products.store') }}',
                                data: $(formEl).serialize(),
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    if (response.isSuccess) {
                                        productCreated(response);
                                    }
                                    $('.is-invalid').removeClass('is-invalid');
                                },
                                beforeSend: function () {
                                    blockUi();
                                },
                                complete: function () {
                                    $(".content-wrapper").unblock();
                                },
                                error: function (response) {
                                    if (response.responseJSON.exception == "InvalidArgumentException") {
                                        Swal.fire({
                                            title: response.responseJSON.message,
                                            icon: 'warning',
                                            timer: 5000,
                                            confirmButtonText: 'فهمیدم!',
                                            buttonsStyling: false,
                                        })
                                    }
                                    $('.is-invalid').removeClass('is-invalid');
                                    $.each(response.responseJSON.errors, function (key, value) {
                                        $('#' + key).addClass('is-invalid');
                                        toastr.warning(value);
                                    });
                                },
                            });
                        }
                    }
                });
                imgDropzone.on('sendingmultiple', function (file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    blockUi(20000,'درحال بارگزاری تصاویر... منتظر بمانید');
                    var data = $("#create-product-form").serializeArray();
                    $.each(data, function (key, el) {
                        formData.append(el.name, el.value);
                    });
                });
            },
            error: function (file, response) {
                console.log(response, "error");
            },
            successmultiple: function (file, response) {

                if (response.isSuccess) {
                    productCreated(response);
                }
            },
            completemultiple: function (file, response) {
                $(".content-wrapper").unblock();
                toastr.success('عکس(ها) با موفقیت بارگزاری شد.');
            },
            reset: function () {
                this.removeAllFiles(true);
            },


        });

        function productCreated(result) {
            let id = result.data;
            let message = result.message;
            Swal.fire({
                title: message,
                text: 'شما به لیست محصولات هدایت می‌شوید!',
                type: 'success',
                icon: 'success',
                timer: 5000,
                showCancelButton: true,
                confirmButtonText: 'ویرایش محصول',
                cancelButtonText: 'لیست محصولات',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.dismiss === Swal.DismissReason.timer) {
                    location.href = "{{ route('products.index') }}";
                } else if (result.value) {
                    let url = "{{ route('products.edit', ':id') }}";

                    url = url.replace(':id', id);
                    location.href = url;

                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    location.href = "{{ route('products.index') }}";
                }
            });
        }

        $("#btn-generate-barcode").on("click", function (event) {
            event.preventDefault();

            $.get("{{ route('products.generate-barcode') }}", function (data, status) {
                if (status) {
                    $('input[name="barcode"]').val(data.barcode);
                }
            });
        });


    </script>
@endsection
