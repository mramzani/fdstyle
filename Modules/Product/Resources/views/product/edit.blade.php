@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('product::products.edit product'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/typography.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/panel/vendor/libs/quill/editor.css')}}"/>
    <script src="{{asset('assets/panel/vendor/libs/quill/katex.js')}}"></script>
    <script src="{{asset('assets/panel/vendor/libs/quill/quill.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/toastr/toastr.css') }}">
@endsection

@section('content')
    <!-- Start Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-start">
            <x-dashboard::breadcrumb :breadcrumb-name="__('product::products.edit product')"></x-dashboard::breadcrumb>
            <div class="">
                <button type="submit" id="create-product-btn"
                        class="btn btn-primary create-product-btn">@lang('dashboard::common.update')</button>
            </div>
        </div>
        <form id="update-product-form">
            @method('PUT')
            <div class="row">
                <div class="col-md-9 col-xl-9 col-sm-12">
                    <!-- product info -->
                    <div class="card mb-4">
                        <div
                            class="card-header bg-primary text-white py-2">@lang('product::products.Basic product specifications')</div>
                        <div class="card-body my-1 p-3">
                            <div class="my-3">
                                <input type="text" id="product_title" name="product_title"
                                       value="{{ $product->name ?? '' }}" class="form-control text-start"
                                       placeholder="@lang('product::products.product name')*">
                                @error('product_title')
                                <x-dashboard::validation-error></x-dashboard::validation-error>
                                @enderror
                            </div>
                            <div class="my-3">
                                <input type="text" id="slug" name="slug" value="{{ $product->slug ?? '' }}"
                                       class="form-control text-start"
                                       placeholder="@lang('product::products.slug')">
                            </div>
                            <div class="input-group">
                                <input type="text" id="barcode" name="barcode" value="{{ $product->barcode ?? '' }}"
                                       class="form-control text-start"
                                       placeholder="@lang('product::products.barcode')">
                                <button class="btn btn-outline-primary" type="button" id="btn-generate-barcode">
                                    @lang('product::products.generate barcode')
                                </button>
                            </div>

                        </div>
                    </div>
                    <!-- product price -->
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 my-1">
                                    <label for="purchase_price"
                                           class="form-label">@lang('product::products.purchase price')</label>
                                    <input type="text" id="purchase_price" name="purchase_price"
                                           value="{{ $product->detail->purchase_price ?? '' }}"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.purchase price')">
                                    <small
                                        class="text-muted form-text">@lang('product::products.Customers will not see this')</small>
                                </div>
                                <div class="col-md-6 col-sm-12 my-1">
                                    <label for="sales_price"
                                           class="form-label">@lang('product::products.sales price')</label>
                                    <input type="text" id="sales_price" name="sales_price"
                                           value="{{ $product->detail->sales_price ?? '' }}"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.sales price')">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 my-1">
                                    <label for="promotion_price"
                                               class="form-label">@lang('product::products.promotion price')</label>
                                    <input type="text" id="promotion_price" name="promotion_price"
                                           value="{{ $product->detail->promotion_price ?? '' }}"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.promotion price')">
                                </div>
                                <div class="col-md-3 col-sm-12 my-1">
                                    <label for="promotion_start_date"
                                               class="form-label">@lang('product::products.promotion start date')</label>
                                    <input type="text" id="promotion_start_date" name="promotion_start_date"
                                           value="{{ old('promotion_start_date',verta($product->detail->promotion_start_date)->format('Y-n-j H:i')) }}"
                                           class="form-control text-start promotion-datetime"
                                           placeholder="@lang('product::products.promotion start date')">
                                </div>
                                <div class="col-md-3 col-sm-12 my-1">
                                    <label for="promotion_end_date"
                                               class="form-label">@lang('product::products.promotion end date')</label>
                                    <input type="text" id="promotion_end_date" name="promotion_end_date"
                                           value="{{ old('promotion_start_date',verta($product->detail->promotion_end_date)->format('Y-n-j H:i')) }}"
                                           class="form-control text-start promotion-datetime"
                                           placeholder="@lang('product::products.promotion end date')">
                                </div>
                                <div class="col-md-3 col-sm-12 my-1">
                                    <label for="offer"
                                           class="form-label">انتخاب آفر</label>
                                    <select name="offer_id" id="offer" class="form-control">
                                        <option value="" selected>یک آفر انتخاب کنید</option>
                                        @foreach(\Modules\PageBuilder\Entities\Offers::where('start_date', '<=', \Illuminate\Support\Carbon::now())
                                            ->where('end_date', '>=', \Illuminate\Support\Carbon::now())->get() as $offer)
                                            <option value="{{ $offer->id }}" @if($product->offer and $product->offer->id == $offer->id) selected @endif>{{ $offer->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product category -->
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="mb-0 col-12">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <label for="category"
                                               class="form-label">@lang('dashboard::common.primary category')</label>
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-0 col-md-6 col-xl-6 col-sm-12">
                                    <label for="secondary_category"
                                           class="form-label">@lang('dashboard::common.secondary category')</label>
                                    <select class="form-select" name="secondary_category[]" id="secondary_category" multiple>
                                        <option value="">@lang('product::products.Select a product category')</option>
                                        @include('product::product.secondary_category',
                                            ['categories' => \Modules\Category\Entities\Category::nested()->get(),'level' => 0])
                                    </select>
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
                                            <option value="{{ $id }}"
                                                    @if($product->brand_id == $id) selected @endif >{{ $brand }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--weight length width height -->
                            <div class="row my-2">
                                <div class="col-md col-12 col-sm-12 my-1">
                                    <label for="weight" class="form-label">وزن بسته‌بندی (گرم)</label>
                                    <input type="number" value="{{ $product->detail->weight }}" id="weight" name="weight"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.weight')">
                                    <small class="text-muted form-text">این مورد اختیاری است.</small>
                                </div>
                                <div class="col-md col-12 col-sm-12 my-1 ">
                                    <label for="length" class="form-label">طول بسته‌بندی (سانتی‌متر)</label>
                                    <input type="number" value="{{ $product->detail->length }}" id="length" name="length"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.length')">
                                    <small class="text-muted form-text">این مورد اختیاری است.</small>
                                </div>
                                <div class="col-md col-12 col-sm-12 my-1">
                                    <label for="width" class="form-label">عرض بسته‌بندی (سانتی‌متر)</label>
                                    <input type="number" value="{{ $product->detail->width }}" id="width" name="width"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.width')">
                                    <small class="text-muted form-text">این مورد اختیاری است.</small>
                                </div>
                                <div class="col-md col-12 col-sm-12 my-1">
                                    <label for="height" class="form-label">ارتفاع بسته‌بندی (سانتی‌متر)</label>
                                    <input type="number" value="{{ $product->detail->height }}" id="height" name="height"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.height')">
                                    <small class="text-muted form-text">این مورد اختیاری است.</small>
                                </div>
                                <div class="col-md col-12 col-sm-12 my-1">
                                    <label for="preparation_time" class="form-label">مدت زمان آماده‌سازی (روز)</label>
                                    <input type="number" value="{{ $product->detail->preparation_time }}" id="preparation_time" name="preparation_time"
                                           class="form-control text-start"
                                           placeholder="@lang('product::products.preparation time')">
                                    <small class="text-muted form-text">این مورد اختیاری است.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product description -->
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div id="snow-toolbar">
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
                                <button class="ql-script" value="sub"></button>
                                <button class="ql-script" value="super"></button>
                              </span>
                                <span class="ql-formats">
                                <button class="ql-header" value="1"></button>
                                <button class="ql-header" value="2"></button>
                                <button class="ql-blockquote"></button>
                                <button class="ql-code-block"></button>
                              </span>
                            </div>
                            <div id="snow-editor">
                                {!! $product->description !!}
                            </div>
                            <textarea name="description" id="description" class="d-none"></textarea>
                        </div>
                    </div>
                    <!-- upload product image -->
                    <div class="card mb-4">
                        <div class="row">
                            <div class="col-8">
                                <div class="card-body">
                                    <div class="dropzone dropzone-area mt-2" id="imgDropzone">
                                        <div
                                            class="dz-message">@lang('product::products.Click here to upload the image')</div>
                                    </div>
                                    <span class="validation-msg" id="image-error"></span>
                                </div>
                                <input type="hidden" id="folder" name="folder" value="product">
                            </div>
                            <div class="col-4">

                                <div class="overflow-auto h-px-300 table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>
                                                <button type="button" class="btn btn-sm"><i class="bx bx-trash"></i>
                                                </button>
                                            </th>
                                            <th>گالری تصاویر</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($product->all_image as $key => $img)
                                            <tr class="img-row" data-rowUniqeId="{{ Str::random(10) }}">
                                                <td>
                                                    <button type="button" data-image="{{ $img }}"
                                                            class="btn btn-sm delete-image"><i class="bx bx-trash"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <img src="{{ $product->getImageUrl($img) }}"
                                                         height="60" width="60" alt="{{ $img }}">
                                                    <input type="hidden" name="prev_img[]" value="{{ $img }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6 col-sm-12 my-1">
                            <div class="card">
                                <div class="card-header">
                                    <span>لطفا اطلاعات جایگاه محصول را در این بخش وارد نمایید</span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 my-1">
                                            <label for="row"
                                                   class="form-label">@lang('product::products.product row')</label>
                                            <input type="text" id="row" name="row"
                                                   value="{{ $product->row ?? '' }}"
                                                   class="form-control text-start"
                                                   placeholder="@lang('product::products.product row')">
                                        </div>
                                        <div class="col-md-6 col-sm-12 my-1">
                                            <label for="floor"
                                                   class="form-label">@lang('product::products.product floor')</label>
                                            <input type="text" id="floor" name="floor"
                                                   value="{{ $product->floor ?? '' }}"
                                                   class="form-control text-start"
                                                   placeholder="@lang('product::products.product floor')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <span>لطفا گارانتی محصول را انتخاب نمایید</span>
                                </div>
                                <div class="card-body p-3">
                                    <div class="form-group my-1">
                                        <label for="guarantee" class="form-label">گارانتی محصول</label>
                                        <select name="guarantee" class="form-control" id="guarantee">
                                            <option value="" selected disabled>انتخاب گارانتی محصول</option>
                                            @foreach(\Modules\Dashboard\Entities\Guarantee::all()->pluck('id','title') as $title => $id )
                                                <option value="{{ $id }}" @if($product->guarantee_id == $id) selected @endif>{{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-3 col-xl-3 col-sm-12">
                    <div class="card mb-4 d-none">
                        <div
                            class="card-header bg-warning text-white py-2">@lang('product::products.product status')</div>
                        <div class="card-body mt-2">
                            <div class="form-check form-check-inline ">
                                <input name="status" class="form-check-input" type="radio" value="enable" id="enable"
                                       @if($product->detail->status == "enable") checked @endif>
                                <label class="form-check-label" for="enable"> @lang('dashboard::common.enable') </label>
                            </div>
                            <div class="form-check form-check-inline ">
                                <input name="status" class="form-check-input" type="radio" value="disable" id="disable"
                                       @if($product->detail->status == "disable") checked @endif>
                                <label class="form-check-label"
                                       for="disable"> @lang('dashboard::common.disable') </label>
                            </div>
                        </div>
                    </div>
                    <div class=" mb-4">
                        <button type="submit" class="btn btn-outline-success d-grid w-100 mb-3 create-product-btn"
                                id="create-product-btn">
                                <span class="d-flex align-items-center justify-content-center text-nowrap">
                                  @lang('dashboard::common.update')
                                </span>
                        </button>
                        <a href="{{ route('products.variants.index',$product) }}"
                           class="btn btn-outline-info d-grid w-100 mb-3">
                                <span class="d-flex align-items-center justify-content-center text-nowrap">
                                    @lang('product::products.manage product variants')
                                </span>
                        </a>
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
    <script src="{{ asset('assets/panel/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jdate/jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/l10n/fa-jdate.js') }}"></script>
    <script type="text/javascript">
        $('.delete-image').on("click", function () {
            $(this).closest("tr").remove()
        });
        toastr.options = {
            "progressBar": false,
            "positionClass": "toast-bottom-left",
            "showDuration": "500",
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "3000",
            "preventDuplicates": true,
        }
        var quill = new Quill('#snow-editor', {
            bounds: '#snow-editor',
            modules: {
                formula: true,
                toolbar: '#snow-toolbar'
            },
            theme: 'snow'
        });

        $("#category").select2({
            placeholder: "@lang('product::products.Select a product category')",
        });
        $("#brand").select2({
            tags: true,
            placeholder: "@lang('product::products.Select a product brand')",
        });
        $("#unit").select2({
            tags: true,
            placeholder: "@lang('product::products.Select a product unit')",
        });
        $("#secondary_category").select2({
            placeholder: "دسته‌بندی فرعی را انتخاب کنید",
        });

        Dropzone.autoDiscover = false;

        const previewTemplate = `<div class="dz-preview dz-file-preview">
                <div class="dz-details">
                  <div class="dz-thumbnail">
                    <img data-dz-thumbnail>
                    <span class="dz-nopreview">@lang('dashboard::common.No preview')</span>
                    <div class="dz-success-mark"></div>
                    <div class="dz-error-mark"></div>
                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                    <div class="progress">
                      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                    </div>
                  </div>
                  <div class="dz-filename" data-dz-name></div>
                  <div class="dz-size" data-dz-size></div>
                </div>
                  </div>`;

        function CantChangeCategoryError(message) {
            Swal.fire({
                title: "خطا در بروزرسانی محصول!",
                text: message,
                type: 'warning',
                icon: 'warning',
                timer: 5000,
                showCancelButton: true,
                showConfirmButton: false,
                //confirmButtonText: "@lang('product::products.product list')",
                cancelButtonText: "فهمیدم!",
                buttonsStyling: false,
            })
        }

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
            url: "{{ route('products.update',$product) }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            init: function () {
                var formEl = "#update-product-form";
                var imgDropzone = this;
                //---------------------------
                $('.create-product-btn').on("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    function saveDescription() {
                        var description = $("textarea[name='description']");
                        description.html(quill.root.innerHTML);
                        //console.log(description);
                    }

                    if ($(formEl).valid()) {
                        if (imgDropzone.getAcceptedFiles().length) {
                            saveDescription();
                            imgDropzone.processQueue();
                        } else {
                            saveDescription();
                            $.ajax({
                                type: "POST",
                                url: '{{ route('products.update',$product) }}',
                                data: $(formEl).serialize(),
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                beforeSend: function () {
                                    blockUi();
                                },
                                complete: function () {
                                    $(".content-wrapper").unblock();
                                },
                                success: function (response) {
                                    $('.is-invalid').removeClass('is-invalid');
                                    if (response.isSuccess) {
                                        productCreated(response);
                                    }
                                },
                                error: function (response) {
                                    if (response.responseJSON.isFailed){
                                        let message = response.responseJSON.message;
                                        CantChangeCategoryError(message);
                                    }
                                    //console.log(response.responseJSON.errors);
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
                    var data = $("#update-product-form").serializeArray();
                    $.each(data, function (key, el) {
                        formData.append(el.name, el.value);
                    });
                });
            },
            error: function (file, response) {
                //console.log(response.errors, "error");
                if (response.isFailed){
                    CantChangeCategoryError(response.message);
                }
                $('.is-invalid').removeClass('is-invalid');
                $.each(response.errors, function (key, value) {
                    $('#' + key).addClass('is-invalid');
                    toastr.warning(value);
                });
            },

            successmultiple: function (file, response) {
                if (response.isSuccess) {
                    productCreated(response);
                }
            },
            completemultiple: function (file, response) {
                $(".content-wrapper").unblock();
                toastr.success("@lang('dashboard::common.Photo(s) uploaded successfully.')");
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
                text: "@lang('dashboard::common.You will be redirected to the product list!')",
                type: 'success',
                icon: 'success',
                timer: 3000,
                showCancelButton: true,
                confirmButtonText: "@lang('product::products.product list')",
                cancelButtonText: "@lang('dashboard::common.cancel')",
                buttonsStyling: false,
            }).then(function (result) {
                if (result.dismiss === Swal.DismissReason.timer) {
                    location.href = "{{ route('products.index') }}";
                }
                if (result.value) {
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

        let flatpickrDatetime = $(".promotion-datetime");
        if (flatpickrDatetime) {
            flatpickrDatetime.flatpickr({
                enableTime: true,
                locale: 'fa',
                altInput: true,
                altFormat: 'Y/m/d - H:i',
                disableMobile: true,
            });
        }


    </script>
@endsection
