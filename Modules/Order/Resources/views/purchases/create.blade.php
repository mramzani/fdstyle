@extends('dashboard::layouts.master')
@section('dashboardTitle','ثبت سفارش خرید جدید')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/toastr/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/spinkit/spinkit.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.css') }}">
    <style>
        ul.ui-widget {
            font-family: "primary-font" !important;
        }

        .ui-menu .ui-menu-item {
            padding: 0.5rem 1rem !important;
            align-items: center !important;
            position: relative;
            display: flex !important;
        }

        .ui-menu-item .ui-menu-item-wrapper {
            padding: 0;
        }

        .ui-menu .ui-menu-item-wrapper:hover {
            color: transparent;
            background-color: transparent;
            border: none;
        }

        .ui-menu .ui-menu-item-wrapper.ui-state-active {
            color: #fff !important;
            background-color: #5a8dee !important;
            border: none;
            border-radius: 0.3125rem;
        }

    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card my-1">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body">
                    <form action="#" method="post" id="posForm">
                        @csrf
                        <!-- Order Info Section -->
                        <!-- Customer select and add customer -->
                        <div class="row my-2">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h6 class="text-warning">شما در حال ثبت سفارش خرید هستید</h6>
                                    <span>بعد از ثبت سفارش خرید، موجودی محصول به صورت خودکار به‌روزرسانی خواهند شد</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="supplier_id" class="form-label">انتخاب تامین کننده</label>
                                    <select class="form-select supplier-list" id="supplier_id"
                                            name="supplier_id"></select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 d-none">
                                <div class="form-group">
                                    <label for="customer_id" class="form-label">‌</label>
                                    <livewire:order::sales.pos.add-customer/>
                                </div>
                            </div>
                        </div>
                        <!-- Order Info Section -->
                        <div class="row">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="search" autocomplete="false"
                                       placeholder="نام محصول یا بارکد | حداقل سه حرف">
                            </div>
                        </div>
                        <!-- product Table section -->
                        <div class="row my-2">
                            <div class="col-12">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover text-center order-list d-none" id="orderList">
                                        <thead class="table-dark">
                                        <tr>
                                            <th class="w-px-50">#</th>
                                            <th class="w-25">نام محصول</th>
                                            <th class="w-25">کد محصول</th>
                                            <th class="w-px-100">تعداد</th>
                                            <th class="w-25">قیمت واحد</th>
                                            <th class="w-25">قیمت کل</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="tbodySection">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <div class="row" id="summery-box">
                                            <div id="inputHiddenSection">
                                                {{--<input type="hidden" id="discount" name="discount" value="0">--}}
                                                <input type="hidden" id="subtotal" name="subtotal"
                                                       value="0">
                                                <input type="hidden" id="total" name="total" value="0">
                                                <input type="hidden" id="paid_amount" name="paid_amount"
                                                       value="0">
                                                <input type="hidden" id="due_amount" name="due_amount"
                                                       value="0">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text" dir="ltr">تعداد اقلام</span>
                                                        <input type="text" name="total_items" id="total_items"
                                                               class="form-control"
                                                               value="0" readonly/>
                                                        <span class="input-group-text" dir="ltr">تعداد کل</span>
                                                        <input type="text" name="total_quantity" id="total_quantity"
                                                               class="form-control"
                                                               value="0"
                                                               readonly/>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text col-3" dir="ltr">مجموع</span>
                                                        <span class="input-group-text col-6"
                                                              id="subtotal_label">0</span>
                                                        <span class="input-group-text col-3">تومان</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text" dir="ltr">تخفیف</span>
                                                        <input type="number" id="discount" name="discount"
                                                               class="form-control" value="0"
                                                               placeholder="مبلغ تخفیف را وارد کنید">
                                                        <button type="button" onclick="applyDiscount()"
                                                                class="btn btn-danger">
                                                            اعمال
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text">کوپن</span>
                                                        <input type="text" class="form-control" readonly
                                                               placeholder="کد کوپن را وارد کنید"
                                                               aria-label="Amount (to the nearest dollar)">
                                                        <button type="button" class="btn btn-primary" id="coupon-btn">
                                                            اعمال
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text" dir="ltr">مالیات</span>
                                                        <select {{--name="tax_amount"--}} class="form-control" id="tax"
                                                                readonly="">
                                                            <option value="0" selected>بدون مالیات</option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text" dir="ltr">حمل و نقل</span>
                                                        <input type="number" name="shipping" id="shipping"
                                                               value="0"
                                                               class="form-control"
                                                               placeholder="هزینه حمل و نقل">
                                                        <button type="button" onclick="applyShipping()"
                                                                class="btn btn-primary"
                                                                id="shipping-btn">
                                                            اعمال
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="w-100 text-center bg-label-success p-1 my-2">
                                            <h4 class="mb-0 text-white">جمع کل:
                                                <span id="total_label"
                                                      class="text-success bold">0</span>
                                                <span class="text-white">تومان</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <div>
                                        <audio id="beep-timber" preload="auto">
                                            <source src="{{ url('assets/panel/audio/beep-timber.mp3') }}"
                                                    type="audio/mpeg"/>
                                        </audio>
                                        <audio id="beep" preload="auto">
                                            <source src="{{url('assets/panel/audio/beep-07.mp3')}}" type="audio/mpeg"/>
                                        </audio>
                                    </div>
                                </div>
                                <div class="col-12 my-1">
                                    <button type="button" id="PaymentBtn" disabled class="btn btn-primary d-grid w-100"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#addPayment">
                                        <span class="d-flex align-items-center justify-content-center text-nowrap">پرداخت (Alt + P)</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Add Payment Sidebar -->
                        <div class="offcanvas offcanvas-end" id="addPayment" aria-hidden="true">
                            <div class="offcanvas-header border-bottom">
                                <h6 class="offcanvas-title">افزودن پرداخت</h6>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                        aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body flex-grow-1">
                                <div class="d-flex justify-content-between bg-lighter p-2 mb-3">
                                    <p class="mb-0">مبلغ قابل پرداخت:</p>
                                    <p class="fw-bold mb-0"><span id="payable_amount">0</span> تومان</p>
                                </div>
                                <div class="d-flex justify-content-between bg-label-success p-2 mb-3">
                                    <p class="mb-0">مبلغ پرداختی:</p>
                                    <p class="fw-bold mb-0"><span id="paying_amount">0</span> تومان</p>
                                </div>
                                <div class="d-flex justify-content-between bg-label-danger p-2 mb-3" id="due_label">
                                    <p class="mb-0" id="due_text">باقی‌مانده بدهکاری:</p>
                                    <p class="fw-bold mb-0"><span id="canvas_due_amount">0</span> تومان</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="paying_amount">مقدار پرداختی</label>
                                    <div class="input-group">
                                        <input type="text" id="paying_amount" onkeyup="changeAmount(this)"
                                               name="paying_amount" value="0"
                                               class="form-control text-start paying_amount">
                                        <span class="input-group-text">تومان</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="payment_mode_id">نحوه پرداخت</label>
                                    <select class="form-select" id="payment_mode_id" name="payment_mode_id">
                                        <option value="" selected disabled>انتخاب نحوه پرداخت</option>
                                        @foreach(\App\Models\PaymentMode::all()->pluck('display_name','id') as $id => $name)
                                            <option value="{{ $id }}"
                                                    @if($id == '4' || $id == '5') disabled @endif>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 d-none" id="referenceIdSection">
                                    <label class="form-label" for="reference_id">پیگیری پرداخت</label>
                                    <input type="text" id="reference_id" name="reference_id"
                                           placeholder="شماره پیگیری پرداخت"
                                           class="form-control text-start">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="payment-note">یادداشت پرداخت</label>
                                    <textarea class="form-control" name="notes" id="notes" rows="2"></textarea>
                                </div>
                                <div class="mb-3 d-flex flex-wrap">
                                    <button type="button" id="saveSale" class="btn btn-primary me-3">ارسال</button>
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">
                                        انصراف
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /Add Payment Sidebar -->
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title secondary-font" id="modalCenterTitle">فاکتور فروش</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-transparent">
                        <div class="row" id="invoice_section">
                        </div>
                    </div>
                    <div class="modal-footer d-none">
                        <button type="button" onclick="printReceipt()" class="btn btn-primary">چاپ</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title secondary-font" id="modalCenterTitle">ویرایش قیمت</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="product-edit-form">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="quantity"> تعداد</label>
                                            <input type="number" name="quantity" id="quantity"
                                                   onkeyup="calculatePrice()" class="form-control" step="any">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="unit_price">هزینه محصول</label>
                                            <input type="number" name="unit_price" id="unit_price"
                                                   onkeyup="calculatePrice()" class="form-control" step="any">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="num_of_gifts">تعداد آفر</label>
                                            <input type="number" name="num_of_gifts" id="num_of_gifts" disabled
                                                   value="0" class="form-control"
                                                   step="any">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="discount"> تخفیف (درصد)</label>
                                            <input type="number" name="discount" id="discount" value="0" disabled
                                                   class="form-control" step="any">
                                        </div>
                                    </div>
                                </div>
                                <h5 class="text-center mt-2">قیمت مصرف کننده</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="profit_percent">درصد حاشیه سود</label>
                                            <input type="number" name="profit_percent" id="profit_percent"
                                                   onkeyup="calculatePrice()" class="form-control" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="profit_calculate_type">نوع محاسبه سود</label>
                                            <select name="profit_calculate_type" id="profit_calculate_type"
                                                    class="form-control">
                                                <option value="1" selected disabled>قیمت بدون تخفیف و آفر</option>
                                                {{--<option value="2">قیمت با احتساب تخفیف</option>
                                                <option value="3">قیمت با احتساب آفر</option>
                                                <option value="4">قیمت با احتساب آفر و تخفیف</option>--}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="tax_rate">مالیات</label>
                                            <select name="tax_rate" id="tax_rate" class="form-control">
                                                <option value="0" data-rate="0" selected disabled>بدون مالیات</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label badge badge-pill bg-label-danger"
                                                   for="final_sum_cost">مجموع مبلغ خرید: </label>
                                            <input type="text" name="final_sum_cost" id="final_sum_cost"
                                                   class="form-control form-control-sm"
                                                   readonly>
                                        </div>
                                        <div class="form-group mt-1">
                                            <label for="final_sum_price"
                                                   class="form-label badge badge-pill bg-label-success">مجموع مبلغ
                                                فروش: </label>
                                            <input type="text" name="final_sum_price" id="final_sum_price"
                                                   class="form-control form-control-sm"
                                                   readonly>
                                        </div>
                                        {{--<div class="form-group">
                                            <label for="total_discount" class="form-label badge badge-pill bg-label-info">مجموع تخفیف + هدیه: </label>
                                            <input type="number" name="total_discount" id="total_discount" value="0" class="form-control form-control-sm" readonly>
                                        </div>--}}
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="final_unit_cost"
                                                   class="form-label badge badge-pill bg-label-danger">قیمت خرید هر
                                                محصول: </label>
                                            <input type="text" name="final_unit_cost" id="final_unit_cost"
                                                   class="form-control form-control-sm"
                                                   readonly>
                                        </div>
                                        <div class="form-group mt-1">
                                            <label for="final_unit_price"
                                                   class="form-label badge badge-pill bg-label-success">قیمت فروش هر
                                                محصول: </label>
                                            <input type="text" name="final_unit_price" id="final_unit_price"
                                                   class="form-control form-control-sm"
                                                   readonly>
                                        </div>
                                        {{-- <div class="form-group">
                                             <label for="total_tax" class="form-label badge badge-pill bg-label-info">مجموع مالیات: </label>
                                             <input type="number" name="total_tax" id="total_tax" value="0" class="form-control form-control-sm" readonly>
                                         </div>--}}
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <button type="button" name="update_row" class="btn btn-primary"
                                            onclick="updatePrice(this)" data-row-id="">بروزرسانی قیمت
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery.ui.autocomplete.html.js') }}"></script>
    <script src="{{ asset('assets/panel/js/order/common-create-order.js') }}"></script>
    <script>

        //#region get supplier by select2 with ajax request
        const supplierListEl = $('.supplier-list');

        supplierListEl.select2({
            ajax: {
                url: "{{ route('dashboard.supplier.get-supplier') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term, // search term
                    };
                },

                processResults: function (response, params) {
                    var select2Data = $.map(response.data, function (obj) {
                        obj.id;
                        obj.full_name = obj.first_name + ' ' + obj.last_name;
                        obj.mobile = obj.mobile;

                        return obj;
                    });
                    return {
                        results: select2Data,
                    };
                },
                cache: true
            },

            minimumInputLength: 2,
            language: {
                inputTooShort: function () {
                    return 'نام‌و‌نام‌خانوادگی یا موبایل تامین کننده را جستجو کنید';
                }
            },

            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo(repo) {
            if (repo.loading) {
                return 'در حال جستجو...';
            }

            return $(
                '<span>' + repo.full_name + ' - ' + repo.mobile + '</span>');
        }

        function formatRepoSelection(repo) {
            return repo.full_name;
        }

        //endregion end get customer select2

        // region autocomplete search and add to table
        $(function () {
            $("#search").autocomplete({
                minLength: 3,
                source: function (request, process) {
                    var productStatus = false;
                    $.ajax({
                        type: "POST",
                        method: "POST",
                        url: "{{ route('autocomplete-search') }}",
                        dataType: "json",
                        data: {
                            term: request.term,
                            productStatus
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
                        success: function (res) {
                            //console.log(res);
                            if (res.length === 1) {
                                addNewRowProduct(res[0]);
                            } else {
                                process(res);
                            }
                        },
                    });
                },
                select: function (event, ui) {
                    event.preventDefault();
                    addNewRowProduct(ui.item, 'purchase');
                }
            })
                .autocomplete("instance")._renderItem = function (ul, item) {
                let stockClass = item.current_stock > 0 ? 'bg-success' : 'bg-danger'
                ul.addClass('list-group position-absolute bg-label-secondary mt-1 border border-gray rounded zindex-5');
                return $("<li class='list-group-item py-2 cursor-pointer d-flex align-items-center text-white'>")
                    .append("<img class='rounded me-3 w-px-50' alt='' src='" + item.image + "' />" +
                        "<div class='w-100 p-2'>" +
                        "<div class='d-flex justify-content-between'>" +
                        "<div class='product-info'>" +
                        "<h6 class='mb-1'> " + item.label + " </h6>" +
                        "<div class='product-status'> <span class='badge badge-dot " + stockClass + " '></span> " +
                        "<small>موجودی: " + item.current_stock + "</small> </div>" + "" +
                        "</div>" +
                        "</div>" +
                        "</div>")
                    .appendTo(ul);
            };
        });

        //endregion

        function addNewRowProduct(item) {
            //show table and hide

            let flag = 1;
            let tbodySection = $("#tbodySection");
            let id = tbodySection.children().length;

            if (id !== 0) {
                id = parseInt($('#orderList tr:last').data('index') + 1);
            }
            if (typeof item.id !== undefined) {
                $(".product_code").each(function () {
                    if ($(this).val() === item.code) {
                        let index = $(this).closest('tr').data('index');
                        let qtyEl = $("#quantity_" + index);
                        let qty = parseInt(qtyEl.val()) + 1;

                        qtyEl.val(qty);
                        flag = 0;
                        playAudioBeep();
                        calculateSubTotal();

                    }
                });
            }

            if (flag) {
                tbodySection.append(createRow({id, item}));
                playAudioBeep();
                calculateSubTotal();
            }

            checkProductTableAndSupplierList();

            checkTableDisplay();
            clearSearchInput();
        }

        let createRow = ({id, item}) => {
            return `
                <tr data-stock="${item.current_stock}" data-index="${id}" class="product-row">
                    <td>${id + 1}</td>
                <td>
                    ${item.name}
                    <small>
                        <span class="badge bg-label-warning">موجودی: ${item.current_stock} عدد</span>
                    </small>
                </td>
                <td>
                    ${item.code}
                </td>
                <td>
                    <input type="text"
                           class="form-control w-px-50 quantity"
                           onkeyup="qtyChange(this,${id})"
                           value="1"
                           id="quantity_${id}"
                           name="products[${id}][quantity]">
                </td>
                <td>
                    <span id="unit_cost_string_${id}">${parseInt(item.purchase_price).toLocaleString(undefined, {minimumFractionDigits: 0})}</span>
                    <input type="hidden"
                           name="products[${id}][unit_price]"
                           id="unit_price_${id}"
                           value="${item.purchase_price}">
                </td>
                <td>
                    <span id="row_price_${id}">${parseInt(item.purchase_price).toLocaleString(undefined, {minimumFractionDigits: 0})}</span>
                    <input type="hidden"
                           name="products[${id}][subtotal]"
                            class="subtotal"
                           id="subtotal_${id}"
                           value="${item.purchase_price}">
                </td>
                <td>
                     <button type="button" onclick="editProductRow(this,${id})" class="btn btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#editModal">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon" onclick="deleteProductRow(this)">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
                <input type="hidden" value="" name="products[${id}][item_id]"
                       id="item_id_${id}">
                <input type="hidden" value="${item.id}" class="productId" id="product_id_${id}"
                       name="products[${id}][product_id]">
                <input type="hidden" id="variant_id_${id}" value="${item.variant_id != null ? item.variant_id : ''}"
                       name="products[${id}][variant_id]">
                <input type="hidden" class="product_code" id="product_code_${id}"
                       value="${item.code}"
                       name="products[${id}][code]">
                <input type="hidden" class="sales_price" id="sales_price_${id}"
                       value="${item.sales_price}"
                       name="products[${id}][sales_price]">
            </tr>
                `;
        }

        //region final submit pos and create sale order
        toastr.options = {
            "progressBar": false,
            "closeButton": true,
            "positionClass": "toast-bottom-right",
            "showDuration": "500",
            "hideDuration": "5000",
            "timeOut": "5000",
            "extendedTimeOut": "3000",
            "preventDuplicates": true,
        }

        var formEl = "#posForm";
        $('#saveSale').on("click", function (event) {
            event.preventDefault();

            if ($(formEl).valid()) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('dashboard.purchase.store') }}',
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
                        if (response) {
                            $("#invoice_section").append(response);
                            $("#addPayment").offcanvas('hide');
                            $('#invoiceModal').modal('show');

                            //resetAll();
                        }
                        $('.is-invalid').removeClass('is-invalid');
                    },
                    error: function (response) {
                        $('.is-invalid').removeClass('is-invalid');
                        let errors = response.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('#' + key).addClass('is-invalid');
                            toastr.warning(value);
                        });
                    },
                    timeout: 3000,
                });
            }
        });

        //endregion

        //region show and hide reference_id input ON change payment mode
        $('select[name="payment_mode_id"]').on("change", function () {
            let payModeId = $(this).val();
            if (payModeId === '2' || payModeId === '3') {
                $('#referenceIdSection').removeClass('d-none');
            } else {
                $('#referenceIdSection').addClass('d-none');
            }
        });
        //endregion

        //region reset add payment form after show off canvas
        var addPaymentOffCanvas = document.getElementById('addPayment');
        addPaymentOffCanvas.addEventListener('show.bs.offcanvas', function () {
            let total = $("#total").val();
            replaceAmount(0, total);
            $("#addPayment input#paying_amount").val(0);
        });

        //endregion

        function checkProductTableAndSupplierList() {
            let countTrEl = $('table#orderList >tbody>tr').length;

            if (supplierListEl.val() != null && countTrEl > 0) {
                enablePaymentBtn();
            } else {
                $('#PaymentBtn').attr('disabled', true);
            }
        }

        function qtyChange(input, rowIndex) {
            //var index = $(input).closest('tr').index();
            let unit_price = $(input).closest('tr').find('#unit_price_' + rowIndex).val();
            let quantity = $(input).closest('tr').find('#quantity_' + rowIndex).val() || 1;
            let total_discount = $(input).closest('tr').find('#total_discount_' + rowIndex).val();
            let subtotal = parseInt(unit_price) * parseInt(quantity);
            //let stock = parseInt($(input).closest('tr').data('stock'));

            //calculateRowProduct
            if (subtotal > 0) {
                //TODO:: calculate total_discount before calculate subtotal
                $('#row_price_' + rowIndex).text(subtotal.toLocaleString(undefined, {minimumFractionDigits: 0}));

                $('#subtotal_' + rowIndex).val(subtotal);
            }
            calculateSubTotal();
        }

        function deleteProductRow(input) {
            $(input).parents('.product-row').remove();
            playAudioBeepTimber();
            calculateSubTotal();
            checkProductTableAndSupplierList();
            checkTableDisplay();
        }

        function calculatePrice() {
            let quantity = parseInt($('#product-edit-form input[name="quantity"]').val());
            let unit_cost = parseInt($('#product-edit-form input[name="unit_price"]').val());
            let profit_percent = parseInt($('#product-edit-form input[name="profit_percent"]').val());

            let final_sum_cost = quantity * unit_cost;
            let final_unit_cost = unit_cost;

            let final_unit_price = final_unit_cost;
            let final_sum_price = final_sum_cost;

            let profit_unit_amount = Math.round(final_unit_price * (profit_percent / 100));
            let profit_sum_amount = Math.round(final_sum_price * (profit_percent / 100));


            //calculate price with profit percent
            if (profit_percent !== 0) {
                final_unit_price = Math.round(final_unit_price + profit_unit_amount); // in
                final_sum_price = Math.round(final_sum_price + profit_sum_amount);
            }

            //let total_discount = (final_sum_cost * discount) + (offer_num * unit_cost);

            $('#product-edit-form input[name="final_sum_cost"]').val(final_sum_cost.toLocaleString(undefined, {minimumFractionDigits: 0}));
            $('#product-edit-form input[name="final_unit_cost"]').val(final_unit_cost.toLocaleString(undefined, {minimumFractionDigits: 0}));
            $('#product-edit-form input[name="final_sum_price"]').val(final_sum_price.toLocaleString(undefined, {minimumFractionDigits: 0}));
            $('#product-edit-form input[name="final_unit_price"]').val(final_unit_price.toLocaleString(undefined, {minimumFractionDigits: 0}));
        }

        function editProductRow(input, rowIndex) {
            let unit_cost = $(input).closest('tr').find('#unit_price_' + rowIndex).val();
            let quantity = $(input).closest('tr').find('#quantity_' + rowIndex).val() || 1;

            $('#product-edit-form input[name="profit_percent"]').val(0);
            $('#product-edit-form input[name="quantity"]').val(quantity);
            $('#product-edit-form input[name="unit_price"]').val(unit_cost);

            $('#product-edit-form button[name="update_row"]').attr('data-row-id', rowIndex);

            calculatePrice();
        }

        function updatePrice(param) {
            let rowIndex = $(param).attr('data-row-id');

            let quantity = $('#product-edit-form input[name="quantity"]').val();
            let unit_cost = $('#product-edit-form input[name="unit_price"]').val();

            let profit_percent = parseInt($('#product-edit-form input[name="profit_percent"]').val());

            let unit_price = Math.round(unit_cost * (profit_percent / 100));



            const tableTrEl = $('#orderList >tbody >tr');
            tableTrEl.find('#quantity_' + rowIndex).val(quantity);
            tableTrEl.find('#unit_cost_string_' + rowIndex).text(parseInt(unit_cost).toLocaleString(undefined, {minimumFractionDigits: 0}));
            tableTrEl.find('#unit_price_' + rowIndex).val(unit_cost);
            tableTrEl.find('#unit_price_' + rowIndex).val(unit_cost);
            tableTrEl.find('#sales_price_' + rowIndex).val(unit_price + parseInt(unit_cost));

            calculateSubTotal();

            $('#editModal').modal('hide');
        }

        supplierListEl.on('change', function () {
            let countTrEl = $('table#orderList >tbody>tr').length;
            if (countTrEl > 0 && $('#PaymentBtn').prop("disabled")) {
                enablePaymentBtn();
            }
        });

        //region reset add payment form after show off canvas
        var addPaymentOffCanvas = document.getElementById('addPayment');
        addPaymentOffCanvas.addEventListener('show.bs.offcanvas', function () {
            let total = $("#total").val();
            replaceAmount(0, total);
            $("#addPayment input#paying_amount").val(0);
        });
        //endregion


    </script>
@endsection
