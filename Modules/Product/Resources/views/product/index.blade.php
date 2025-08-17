@extends('dashboard::layouts.master')
@section('dashboardTitle',trans('product::products.product list'))
@section('content')
    <!-- Start Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-start">
            <x-dashboard::breadcrumb :breadcrumb-name="__('product::products.product list')"></x-dashboard::breadcrumb>
        </div>
        <div class="card my-2">
            <div class="card-body">
                <div class="col-12 my-1">
                    <x-product::product-alert
                        :count="\Modules\Product\Entities\Product::withoutImage()"
                        :variable="trans('dashboard::common.image')"></x-product::product-alert>

                    <x-product::product-alert
                        :count="\Modules\Product\Entities\Product::withoutBrand()"
                        :variable="trans('dashboard::common.brand')"></x-product::product-alert>

                    <x-product::product-alert
                        :count="\Modules\Product\Entities\Product::withoutCategory()"
                        :variable="trans('dashboard::common.category')"></x-product::product-alert>


                    @if(\Modules\Product\Entities\Product::stockOut() != 0)
                        <div class="alert alert-warning">
                            @lang('product::products.there is product stockOut',
                                    ['count' => \Modules\Product\Entities\Product::stockOut()])
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <livewire:product::products.product-list />
        <!-- region Show Sale Detail offCanvas -->
        <div class="offcanvas offcanvas-end w-px-800"
             style="overflow-y: auto"
             data-bs-scroll="true"
             data-bs-backdrop="true" tabindex="-1" id="offcanvasShowVariant"
             aria-labelledby="offcanvasEndLabel">
            <div id="variant_section"></div>
        </div>
        <!--/ endregion Show Sale Detail offCanvas -->

        <!-- region Show Stock Histories offCanvas -->
        <div class="offcanvas offcanvas-end w-px-800"
             style="overflow-y: auto"
             data-bs-scroll="true"
             data-bs-backdrop="true" tabindex="-1" id="offcanvasShowStockHistory"
             aria-labelledby="offcanvasEndLabel">
            <div id="stock_histories_section"></div>
        </div>
        <!--/ endregion Show Stock Histories offCanvas -->
    </div>
    <!-- End Content -->
@endsection

@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script>

        $(".delete-product").on('click', function (event) {
            event.preventDefault();
            const btn = $(this);
            let id = btn.data("id");
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
                    $("#deleteProductConfirm-" + id).submit();
                }
            });
        });

        $("#updatePrice").on('click', function (event) {
            event.preventDefault();
            Swal.fire({
                title: "از بروزرسانی قیمت اطمینان دارید؟",
                text: "در صورت بروزرسانی قیمت فروش با تنوع محصول این عملیات غیرقابل بازگشت می‌باشد.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "@lang('dashboard::common.confirm')",
                cancelButtonText: "@lang('dashboard::common.cancel')",
                buttonsStyling: false,
            }).then(function (value) {
                if (!value.dismiss) {
                    $("#updatePriceForm").submit();
                }
            });
        });

        function variantList(productId) {
            if (!isNaN(productId)) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('product.variants.list') }}',
                    data: {
                        product_id: productId
                    },
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
                        if (response) {
                            $("#variant_section").html(response);
                        }
                    },
                    error: function (response) {
                        console.log(response);

                    },
                    timeout: 3000,
                });
            }
        }

        function getStockHistory(productId) {
            if (!isNaN(productId)) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('product.stock-histories') }}',
                    data: {
                        product_id: productId
                    },
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
                        if (response) {
                            $("#stock_histories_section").html(response);
                        }
                    },
                    error: function (response) {
                        console.log(response);

                    },
                    timeout: 3000,
                });
            }
        }
    </script>
@endsection
