@extends('dashboard::layouts.master')
@section('dashboardTitle','افزودن تنظیم موجودی')
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
                    <form action="{{ route('adjustments.store') }}" method="post" id="stockAdjustmentForm">
                        @csrf
                        <div class="card-body">
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
                                                <th class="w-px-100">نوع</th>
                                                <th>عملیات</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0" id="tbodySection">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
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
                                            <span class="d-flex align-items-center justify-content-center text-nowrap"> ایجاد تنظیم موجودی </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
    <script>
        // region autocomplete search and add to table
        $(function () {
            $("#search").autocomplete({
                minLength: 3,
                source: function (request, process) {
                    var productStatus = false;
                    $.ajax({
                        type: "POST",
                        method: "POST",
                        url: "{{ route('autocomplete-adjustment-search') }}",
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
                                addNewRowProduct(res[0]);
                            } else {
                                process(res);
                            }
                        },
                    });
                },
                select: function (event, ui) {
                    event.preventDefault();
                    addNewRowProduct(ui.item);
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
                        "<div class='product-status'> <span class='badge badge-dot bg-info'></span> " +
                        "<small>موجودی: " + item.current_stock + "</small> </div>" + "" +
                        "</div>" +
                        "</div>" +
                        "</div>")
                    .appendTo(ul);
            };
        });
        //endregion
        //----region START for sales create
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
                           value="1"
                           id="quantity_${id}"
                           name="products[${id}][quantity]">
                </td>
                <td>
                   <select class="form-control w-auto" name="products[${id}][action]">
                                <option value="add">افزودن</option>
                                <option value="subtract">کاهش</option>
                            </select>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-icon" onclick="deleteProductRow(this)">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>

                <input type="hidden" value="${item.id}" class="productId" id="product_id_${id}"
                       name="products[${id}][product_id]">
                <input type="hidden" id="variant_id_${id}" value="${item.variant_id != null ? item.variant_id : ''}"
                       name="products[${id}][variant_id]">
            </tr>
                `;
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
                        /*if (parseInt($("#product_code_" + index).val()) === item.code) {*/
                        let qtyEl = $("#quantity_" + index);
                        let qty = parseInt(qtyEl.val()) + 1;


                        /*if (qty > item.current_stock) {
                            alert('مقدار وارد شده بیشتر از تعداد موجودی می باشد! ');
                            flag = 0;
                            return false;
                        }*/


                        qtyEl.val(qty);
                        flag = 0;
                        playAudioBeep();

                    }
                });
            }

            if (flag) {
                tbodySection.append(createRow({id, item}));
                playAudioBeep();
                //calculateSubTotal();
            }

            checkTableDisplay();
            clearSearchInput();
        }

        function playAudioBeep() {
            const audio = $("#beep")[0];
            audio.play();
        }

        function qtyChange(input, rowIndex) {
            //var index = $(input).closest('tr').index();
            let unit_price = $(input).closest('tr').find('#unit_price_' + rowIndex).val();
            let quantity = $(input).closest('tr').find('#quantity_' + rowIndex).val() || 1;
            let total_discount = $(input).closest('tr').find('#total_discount_' + rowIndex).val();
            let subtotal = parseInt(unit_price) * parseInt(quantity);
            let stock = parseInt($(input).closest('tr').data('stock'));
            //console.log(total_discount);
            if (quantity > stock) {
                alert('تعداد وارد شده بیشتر از موجودی می‌باشد!');
                $(input).closest('tr').find('#quantity_' + rowIndex).val(stock);
                subtotal = parseInt(unit_price) * stock;
            }

            //calculateRowProduct
            if (subtotal > 0) {
                //TODO:: calculate total_discount before calculate subtotal
                $('#row_price_' + rowIndex).text(subtotal.toLocaleString(undefined, {minimumFractionDigits: 0}));

                $('#subtotal_' + rowIndex).val(subtotal);
            }
            calculateSubTotal();
        }

        function checkTableDisplay() {
            let countTrEl = $('table#orderList >tbody>tr').length;
            if (countTrEl > 0) {
                showTable();
            } else if (countTrEl <= 0) {
                hideTable();
            }
        }

        function showTable() {
            $("table#orderList").removeClass('d-none');
        }
        function hideTable() {
            $("table#orderList").addClass('d-none');
        }

        function clearSearchInput() {
            $("#search").val('');
        }
        function deleteProductRow(input) {
            $(input).parents('.product-row').remove();
            playAudioBeepTimber();
            checkTableDisplay();
        }
        function playAudioBeepTimber() {
            const audio = $("#beep-timber")[0];
            audio.play();
        }

        //----endregion END for sales create
    </script>
@endsection
