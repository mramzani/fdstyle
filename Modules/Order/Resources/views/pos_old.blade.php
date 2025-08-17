@extends('order::layouts.pos_layout')
@section('dashboardTitle','صندوق فروش')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/select2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/toastr/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/spinkit/spinkit.css') }}"/>
    <style>
        #template-customizer {
            visibility: hidden !important;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid align-items-center" id="card-block">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <form action="{{ route('dashboard.pos.store') }}" method="post" id="posForm">
                        @csrf
                        <div class="card-body">
                            <div class="row mt-1 mb-2">
                                <div class="col-6 col-lg-7">
                                    <div class="form-group">
                                        <select class="form-select customer-list" id="customer_id"
                                                name="customer_id"></select>
                                    </div>
                                </div>

                                <livewire:order::sales.pos.add-customer/>

                            </div>
                            <div class="mb-3">
                                <livewire:product::products.product-search-input :showAvailableProduct="true"/>
                            </div>
                            <div class="mb-2">
                                <livewire:order::pos-result/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">

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
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    {{--<script src="https://jasonday.github.io/printThis/printThis.js"></script>--}}
    <script>
        document.onkeydown = function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                return false;
            }
            if (event.altKey === true && event.code === 'KeyP') {
                $("#PaymentBtn").trigger('click');
            }
        };
        //--------------------------------

        window.addEventListener('calculateSubTotal', event => {
            calculateSubTotal();
        });

        function qtyChange(input, rowIndex) {
            //var index = $(input).closest('tr').index();
            let unit_price = $(input).closest('tr').find('#unit_price_' + rowIndex).val();
            let quantity = $(input).closest('tr').find('#quantity_' + rowIndex).val() || 1;
            let total_discount = $(input).closest('tr').find('#total_discount_' + rowIndex).val();
            let subtotal = parseInt(unit_price) * parseInt(quantity);
            let stock = parseInt($(input).closest('tr').data('stock'));
            if (quantity > stock) {
                alert('تعداد وارد شده بیشتر از موجودی می‌باشد!');
                $(input).closest('tr').find('#quantity_' + rowIndex).val(stock);
                subtotal = parseInt(unit_price) * stock;
            }
            //calculateRowProduct
            if (subtotal > 0) {
                $('#row_price_' + rowIndex).text(subtotal.toLocaleString(undefined, {minimumFractionDigits: 0}));

                $('#subtotal_' + rowIndex).val(subtotal);
            }
            calculateSubTotal();
        }

        function calculateSubTotal() {
            //calculate total_items

            const tableTrEl = $('#orderList >tbody >tr');
            var total_items = tableTrEl.length;

            //calculate total_quantity

            var total_quantity = 0;
            var subtotal = 0;

            tableTrEl.each(function () {
                total_quantity += parseInt($(this).find('.quantity').val()) || 1;
                subtotal += parseInt($(this).find('.subtotal').val());
            })


            // sum all subtotal
            $('#total_items').val(total_items);
            $('#total_quantity').val(total_quantity);
            $('#subtotal').val(subtotal);
            $('#subtotal_label').text(subtotal.toLocaleString(undefined, {minimumFractionDigits: 0}));

            // sum all discount + discount amount
            calculateTotal();

        }

        function calculateTotal() {
            let subtotal = parseInt($('#subtotal').val());
            let shipping = parseInt($('#shipping').val());
            let discount = parseInt($('#discount').val());

            let total = subtotal;

            if (shipping !== 0) {
                total += shipping;
            }
            if (discount !== 0) {
                total -= discount;
            }

            $('#total_label').text(total.toLocaleString(undefined, {minimumFractionDigits: 0}));

            $('#total').val(total);
            $('#payable_amount').text(total.toLocaleString(undefined, {minimumFractionDigits: 0}))
            $('#canvas_due_amount').text(total.toLocaleString(undefined, {minimumFractionDigits: 0}))

        }

        function deleteProductRow(input) {
            $(input).closest('tr').remove();
            checkProductTableAndCustomerList();
        }

        function playAudioBeepTimber() {
            const audio = $("#beep-timber")[0];
            audio.play();
        }

        function applyDiscount() {

            var discount = parseInt($('input[name="discount"]').val());

            let total = $('#total').val();

            if (isNaN(discount)) {
                alert('مقدار تخفیف نباید خالی باشد!');
                return;
            }
            if (discount >= total) {
                alert('مقدار تخفیف نباید بیشتر یا مساوی مجموع باشد!');
                return;
            }

            $('#discount').val(discount);

            calculateTotal();
        }

        function applyShipping() {
            var shipping = parseInt($('#shipping').val());
            if (isNaN(shipping)) {
                alert('مقدار حمل‌و‌نقل نباید خالی باشد!');
                return;
            }

            calculateTotal();
        }


        function checkPayingAmount(payingAmount, total) {
            let dueLabel = $('#due_label');
            if (payingAmount > total){
                $('#due_text').text('باقی‌مانده بستانکاری:');
                dueLabel.removeClass('bg-label-danger');
                dueLabel.addClass('bg-label-warning');
            } else if (payingAmount <= total){
                $('#due_text').text('باقی‌مانده بدهکاری:');
                dueLabel.removeClass('bg-label-warning');
                dueLabel.addClass('bg-label-danger');
            }
        }

        function changeAmount(input) {
            let payingAmount = $(input).val() !== '' ? parseInt($(input).val()) : 0;
            let total = parseInt($('#total').val());
            let dueAmount;


            if (payingAmount > total) {
                alert('مبلغ پرداختی نمی‌تواند بیشتر از مبلغ فاکتور باشد.');
                $(input).val(total);
                payingAmount = total;
                dueAmount = 0;
            } else {
                dueAmount = total - payingAmount;
            }

            //check paying amount and change label color , for when allowed paying amount > total amount
            //چک کردن مبلغ درحال پرداخت،تغییر رنگ بکگراند،برای زمانی که اجازه بدیم مبلغ پرداخت بیشتر از مبلغ فاکتور باشد
            //checkPayingAmount(payingAmount,total);

            $('#paid_amount').val(payingAmount);
            $('#due_amount').val(dueAmount);
            $('#paying_amount').text(payingAmount.toLocaleString(undefined, {minimumFractionDigits: 0}));
            $('#canvas_due_amount').text(Math.abs(dueAmount).toLocaleString(undefined, {minimumFractionDigits: 0}));
        }
        //--------------------------------

        //#region get customer select2
        $('.customer-list').select2({
            ajax: {
                url: "{{ route('dashboard.customer.get-customer') }}",
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
                    return 'نام‌و‌نام‌خانوادگی یا موبایل مشتری را جستجو کنید';
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

        //region check select customer and exists product in table order list

        $(".customer-list").on('change', function () {
            let countTrEl = $('table#orderList >tbody>tr').length;
            if (countTrEl > 0 && $('#PaymentBtn').prop("disabled")) {
                enablePaymentBtn();
            }
        });

        window.addEventListener('productAddedToTable', event => {
            playAudioBeep();
            checkProductTableAndCustomerList();
        });

        function checkProductTableAndCustomerList() {
            let countTrEl = $('table#orderList >tbody>tr').length;
            if ($('.customer-list').val() != null && countTrEl > 0) {
                enablePaymentBtn();
            }
        }

        function playAudioBeep() {
            const audio = $("#beep")[0];
            audio.play();
        }

        function enablePaymentBtn() {
            $('#PaymentBtn').attr('disabled', false);
        }

        //endregion

        //region submit pos
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
                    url: '{{ route('dashboard.pos.store') }}',
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
                            resetAll();
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

        $('select[name="payment_mode_id"]').on("change", function () {
            let payModeId = $(this).val();
            if(payModeId === '2' || payModeId === '3'){
                $('#referenceIdSection').removeClass('d-none');
            } else {
                $('#referenceIdSection').addClass('d-none');
            }
        });

        function resetAll() {
            setTimeout(function () {
                location.reload()
            }, 5000);
        }


    </script>
@endsection
