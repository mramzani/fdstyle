@extends('dashboard::layouts.master')
@section('dashboardTitle','پرینت بارکد محصولات')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.css') }}">
    <style>
        ul.ui-widget {
            font-family: "primary-font", sans-serif !important;
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

        .barcode-main {
            border: 1px solid #ccc;
            display: block;
            margin: 10px auto;
            padding: 0.1in;
            page-break-after: always;
            width: 5.7in;
        }

        .barcode-main .barcode-item {
            border: 1px dotted #ccc;
            display: block;
            float: left;
            font-size: 12px;
            line-height: 14px;
            overflow: hidden;
            text-align: center;
            text-transform: uppercase;
        }

        .barcode-main .barcode-style {
            padding: 0.12in;
            width: 1.799in;
        }

    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card my-1">
                <div class="card-body">
                    <div class="alert alert-solid-info">محصول را جستجو کنید و به لیست اضافه کنید</div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="search" autocomplete="false"
                                   placeholder="نام محصول یا بارکد | حداقل سه حرف">
                        </div>
                    </div>
                    <div class="row my-2 d-none" id="tblSection">
                        <div class="col-12">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover text-center barcode-list" id="barcodeList">
                                    <thead class="table-dark">
                                    <tr>
                                        <th class="w-px-50">#</th>
                                        <th class="w-25">نام محصول</th>
                                        <th class="w-25">کد محصول</th>
                                        <th class="w-px-100">تعداد</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0" id="tbodySection">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4">
                                <button type="button" id="demoBtn" class="btn btn-primary d-grid w-100">
                                    <span
                                        class="d-flex align-items-center justify-content-center text-nowrap">پیش‌نمایش</span>
                                </button>
                            </div>
                            <div class="col-4">
                                <button type="button" id="resetBtn" class="btn btn-outline-danger d-grid w-100">
                                    <span
                                        class="d-flex align-items-center justify-content-center text-nowrap">ریست</span>
                                </button>
                            </div>
                            <div class="col-4">
                                <button type="button" id="printBtn" class="btn btn-secondary d-grid w-100">
                                    <span
                                        class="d-flex align-items-center justify-content-center text-nowrap">چاپ</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 d-flex d-wrap justify-content-between overflow-auto my-2"
                             id="printSection">
                            <div class="barcode-main d-none" id="demo"></div>
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
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jquery-ui/jquery.ui.autocomplete.html.js') }}"></script>

    <script>
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
                            productStatus,
                            with: 'barcode',
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

        function addNewRowProduct(item) {
            //show table and hide

            let flag = 1;
            let tbodySection = $("#tbodySection");
            let id = tbodySection.children().length;

            if (id !== 0) {
                id = parseInt($('#barcodeList tr:last').data('index') + 1);
            }
            if (typeof item.id !== undefined) {
                $(".product_code").each(function (index) {
                    if ($(this).val() === item.code) {

                        let qtyEl = $("#quantity_" + index);
                        let qty = parseInt(qtyEl.val()) + 1;
                        qtyEl.val(qty);
                        flag = 0;
                        playAudioBeep();
                    }
                });
            }

            if (flag) {
                tbodySection.append(createRow({id, item}));
                playAudioBeep();
            }

            checkTableDisplay();
            $("#search").val('');
            $("#tblSection").removeClass('d-none');
        }

        function playAudioBeep() {
            const audio = $("#beep")[0];
            audio.play();
        }

        function checkTableDisplay() {
            let countTrEl = $('table#barcodeList >tbody>tr').length;
            if (countTrEl > 0) {
                $("table#barcodeList").removeClass('d-none');
            } else if (countTrEl <= 0) {
                $("table#barcodeList").addClass('d-none');
            }
        }

        let createRow = ({id, item}) => {

            return `
                <tr data-index="${id}" class="product-row">
                    <td>${id + 1}</td>
                <td>
                   <span class="product_name"> ${item.name}</span>
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
                    <button type="button" class="btn btn-sm btn-icon" onclick="deleteProductRow(this)">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
                <input type="hidden" value="${item.barcode}" class="barcode" name="barcode">
                <input type="hidden" value="${item.name}" name="products[${id}][product_name]"
                       id="product_name_${id}">
                <input type="hidden" value="${item.id}" class="productId" id="product_id_${id}"
                       name="products[${id}][product_id]">
                <input type="hidden" class="product_code" id="product_code_${id}"
                       value="${item.code}"
                       name="products[${id}][code]">
            </tr>
                `;
        }

        function deleteProductRow(input) {
            $(input).parents('.product-row').remove();
            playAudioBeepTimber();
            checkTableDisplay();
        }

        const demoEl = $("#demo");
        $("#demoBtn").on("click", function () {
            demoEl.html("");
            demoEl.removeClass('d-none');
            var code = [];
            var name = [];
            var qty = [];
            var barcode = [];
            var tbodySection = $("table.barcode-list tbody#tbodySection");
            var index = tbodySection.children().length;

            for (let i = 0; i < index; i++) {
                let rowEl = 'table.barcode-list tbody tr:nth-child(' + (i + 1) + ')';

                code.push($(rowEl).find(".product_code").val());
                name.push($(rowEl).find(".product_name").text());
                barcode.push($(rowEl).find(".barcode").val());
                qty.push($(rowEl).find(".quantity").val());
                //qty += parseInt($(rowEl).find(".quantity").val());
            }

            $.each(qty, function (index) {
                let row = 0;
                while (row < qty[index]) {
                    $("#demo").append(
                        '<div class="col-md-4 barcode-item barcode-style">' +
                        '<div class="mb-2">' + name[index] + '</div><img src="data:image/png;base64,' + barcode[index] + '" class="w-100" alt="">' +
                        '<div class="mt-2 fw-bolder" style="letter-spacing: 5px">' + code[index] + '</div> </div>');

                    row++;
                }

            });

        });

        $("#printBtn").on("click", function () {
            var tbodyLength = $("table.barcode-list tbody#tbodySection").length;

            if (tbodyLength > 0 && $("#demo").html() !== "") {
                var divToPrint = document.getElementById('printSection');
                // var newWin = window.open('', 'Print-Window');
                var newWin = window.open('', 'name', 'directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=500,top=50,left=1000');
                newWin.document.open();
                newWin.document.write('<link href="{{ asset('assets/panel/vendor/css/rtl/core.css') }}" rel="stylesheet" ><style>.barcode-main {display: block;margin: 10px auto;padding: 0.1in;page-break-after: always;width: 5.7in;} .barcode-main .barcode-item {border: 1px dotted #ccc;display: block;float: left;font-size: 12px;line-height: 14px;overflow: hidden;text-align: center;text-transform: uppercase;}.barcode-main .barcode-style {padding: 0.12in;width: 1.799in;}</style><body onload="print()">' + divToPrint.innerHTML + '</body>');
                newWin.document.close();
            } else {
                $("#demoBtn").trigger("click");
                $("#printBtn").trigger("click");
            }

        });

        $("#resetBtn").on("click",function () {
            $("table.barcode-list tbody#tbodySection").html("");
            demoEl.html("");
            demoEl.addClass("d-none");
        });

    </script>
@endsection
