@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش خرید')
@section('styles')
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
                    <form action="{{ route('dashboard.purchase.update',$purchase->id) }}" method="post" id="posForm">
                        @csrf
                        <!-- Order Info Section -->
                        <!-- supplier select and add supplier -->
                        <div class="row">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="invoice_number" class="form-label">شماره فاکتور</label>
                                <input type="text" id="invoice_number" name="invoice_number" readonly
                                       value="{{ $purchase->invoice_number }}" class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="supplier" class="form-label">نام مشتری</label>
                                <input type="text" id="supplier" value="{{ $purchase->supplier->full_name }}" readonly
                                       class="form-control">
                                <input type="hidden" id="supplier_id" value="{{ $purchase->supplier->id }}"
                                       name="supplier_id">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="date" class="form-label">تاریخ فاکتور</label>
                                <input type="text" readonly id="date" name="date"
                                       value="{{ verta($purchase->order_date)->formatDate() }}" class="form-control">
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
                                    <table class="table table-hover text-center order-list" id="orderList">
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
                                        @foreach($purchase->products as $product)
                                            @php
                                                $product->pivot->variant == null
                                                ? $current_stock = $product->pivot->product->detail->current_stock
                                                : $current_stock = $product->pivot->variant->quantity
                                            @endphp
                                            <tr data-stock="{{ $product->pivot->variant == null ? $product->pivot->product->detail->current_stock : $product->pivot->variant->quantity }}"
                                                data-index="{{ $loop->index }}" class="product-row">
                                                <td>{{ $loop->index + 1}}</td>
                                                <td>
                                                    {{ $product->pivot->product->name }} {{ $product->pivot->variant != null ? ' - ' . $product->pivot->variant->option->valuable->title : '' }}
                                                    <small>
                                                        <span class="badge bg-label-warning">موجودی: {{ $current_stock }} عدد</span>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span
                                                        dir="ltr">{{ $product->pivot->variant == null ? $product->pivot->product->barcode : $product->pivot->variant->code }}</span>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="form-control w-px-50 quantity"
                                                           onkeyup="qtyChange(this,{{ $loop->index }})"
                                                           value="{{ $product->pivot->quantity }}"
                                                           id="quantity_{{ $loop->index }}"
                                                           name="products[{{ $loop->index }}][quantity]">
                                                </td>
                                                <td>
                                                    <span>{{ number_format($product->pivot->unit_price) }}</span>
                                                    <input type="hidden"
                                                           name="products[{{ $loop->index }}][unit_price]"
                                                           id="unit_price_{{ $loop->index }}"
                                                           value="{{ $product->pivot->unit_price }}">
                                                </td>
                                                <td>
                                                    <span
                                                        id="row_price_{{ $loop->index }}">{{ number_format($product->pivot->subtotal) }}</span>
                                                    <input type="hidden"
                                                           name="products[{{ $loop->index }}][subtotal]"
                                                           class="subtotal"
                                                           id="subtotal_{{ $loop->index }}"
                                                           value="{{ $product->pivot->subtotal }}">
                                                </td>
                                                <td>
                                                    {{--@if($product->pivot->quantity <= $current_stock )--}}
                                                        <button type="button" class="btn btn-sm btn-icon"
                                                                onclick="deleteProductRow(this)">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    {{--@endif--}}


                                                </td>
                                                <input type="hidden" value=""
                                                       name="products[{{ $loop->index }}][item_id]"
                                                       id="item_id_{{ $loop->index }}">
                                                <input type="hidden" value="{{ $product->pivot->product_id }}"
                                                       class="productId" id="product_id_{{ $loop->index }}"
                                                       name="products[{{ $loop->index }}][product_id]">
                                                <input type="hidden" id="variant_id_{{ $loop->index }}"
                                                       class="variantId" value="{{ $product->pivot->variant_id }}"
                                                       name="products[{{ $loop->index }}][variant_id]">
                                                <input type="hidden" class="product_code"
                                                       id="product_code_{{ $loop->index }}"
                                                       value="{{ $product->pivot->product->barcode }}"
                                                       name="products[{{ $loop->index }}][code]">
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <div class="row" id="summery-box">
                                            <div id="inputHiddenSection">
                                                {{--<input type="hidden" id="discount" name="discount" value="0">--}}
                                                <input type="hidden" id="subtotal" name="subtotal"
                                                       value="{{ $purchase->subtotal }}">
                                                <input type="hidden" id="total" name="total" value="{{ $purchase->total }}">
                                                <input type="hidden" id="paid_amount" name="paid_amount"
                                                       value="{{ $purchase->paid_amount }}">
                                                <input type="hidden" id="due_amount" name="due_amount"
                                                       value="{{ $purchase->due_amount }}">
                                                <input type="hidden" id="removed_items" name="removed_items" value="">
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text" dir="ltr">تعداد اقلام</span>
                                                        <input type="text" name="total_items" id="total_items"
                                                               class="form-control"
                                                               value="{{ $purchase->total_items }}" readonly/>
                                                        <span class="input-group-text" dir="ltr">تعداد کل</span>
                                                        <input type="text" name="total_quantity" id="total_quantity"
                                                               class="form-control"
                                                               value="{{ $purchase->total_quantity }}"
                                                               readonly/>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text col-3" dir="ltr">مجموع</span>
                                                        <span class="input-group-text col-6"
                                                              id="subtotal_label">{{ number_format($purchase->subtotal) }}</span>
                                                        <span class="input-group-text col-3">تومان</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <span class="input-group-text" dir="ltr">تخفیف</span>
                                                        <input type="number" id="discount" name="discount"
                                                               class="form-control" value="{{ $purchase->discount }}"
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
                                                               value="{{ $purchase->shipping }}"
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
                                                      class="text-success bold">{{ number_format($purchase->total) }}</span>
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
                                    <button type="submit" class="btn btn-primary d-grid w-100">
                                        <span class="d-flex align-items-center justify-content-center text-nowrap">ذخیره (Alt + P)</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery.ui.autocomplete.html.js') }}"></script>
    <script src="{{ asset('assets/panel/js/order/common-create-order.js') }}"></script>
    <script>
        const supplierListEl = $('.supplier-list');

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
                            if (res.length === 1) {
                                addNewRowProduct(res[0],'purchase');
                            } else {
                                process(res);
                            }
                        },
                    });
                },
                select: function (event, ui) {
                    event.preventDefault();
                    addNewRowProduct(ui.item,'purchase');
                }
            })
                .autocomplete("instance")._renderItem = function (ul, item) {
                ul.addClass('list-group position-absolute bg-label-secondary mt-1 border border-gray rounded zindex-5');
                return $("<li class='list-group-item py-2 cursor-pointer d-flex align-items-center text-white'>")
                    .append("<img class='rounded me-3 w-px-50' alt='' src='" + item.image + "' />" +
                        "<div class='w-100 p-2'>" +
                        "<div class='d-flex justify-content-between'>" +
                        "<div class='product-info'>" +
                        "<h6 class='mb-1'> " + item.label + " </h6>" +
                        "<div class='product-status'> <span class='badge badge-dot bg-success'></span> " +
                        "<small>موجودی: " + item.current_stock + "</small> </div>" + "" +
                        "</div>" +
                        "</div>" +
                        "</div>")
                    .appendTo(ul);
            };
        });

        var removed = [];

        function deleteProductRow(input) {
            var id = $(input).closest('tr').find('.productId').val();
            var variantId = $(input).closest('tr').find('.variantId').val();
            $(input).parents('.product-row').remove();
            playAudioBeepTimber();
            calculateSubTotal();
            checkProductTableAndSupplierList();
            checkTableDisplay();

            addToRemovedItem(id, variantId);
        }

        function addToRemovedItem(item, variantId) {
            removed.push({
                'productId': item ,
                'variantId' : variantId,
            });

            var removedArr = JSON.stringify(removed);

            $("#removed_items").val(removedArr);
        }

        function addNewRowProduct(item) {
            //show table and hide

            let flag = 1;
            let tbodySection = $("#tbodySection");
            let id = tbodySection.children().length;

            if (id !== 0) {
                id = parseInt($('#orderList tr:last').data('index') + 1);
            }
            if (typeof item.id !== undefined) {
                $(".product_code").each(function (index) {
                    if ($(this).val() === item.code) {

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
                    <span>${parseInt(item.purchase_price).toLocaleString(undefined, {minimumFractionDigits: 0})}</span>
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
            </tr>
                `;
        }
        // override qty change method
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

        function checkProductTableAndSupplierList() {
            let countTrEl = $('table#orderList >tbody>tr').length;
            if (supplierListEl.val() != null && countTrEl > 0) {
                enablePaymentBtn();
            } else {
                $('#PaymentBtn').attr('disabled', true);
            }
        }
    </script>
@endsection
