@extends('dashboard::layouts.master')
@section('dashboardTitle','لیست خرید')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/toastr/toastr.css') }}">
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl-12">
                <h6 class="text-muted">لیست خرید</h6>
                @include('dashboard::partials.alert')
                <livewire:order::purchases.purchases-list />
            </div>
        </div>
        <!-- region Show Sale Detail offCanvas -->
        <div class="offcanvas offcanvas-end w-px-800"
             style="overflow-y: auto"
             data-bs-scroll="true"
             data-bs-backdrop="true" tabindex="-1" id="offcanvasShowSaleDetails"
             aria-labelledby="offcanvasEndLabel">
            <div id="detail_section"></div>
        </div>
        <!--/ endregion Show Sale Detail offCanvas -->
        <!-- region Add Payment Modal -->
        @can('order_payments_create')
            <div class="modal fade" id="addPayment" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title secondary-font" id="addPaymentLabel">افزودن پرداخت جدید</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addPayForm" method="post">
                            @csrf
                            <input type="hidden" id="order_id" name="order_id" value="">
                            <input type="hidden" id="total" name="total" value="">
                            <div class="modal-body" id="hasEmpty">
                                <div class="col mb-3">
                                    <label for="date" class="form-label">تاریخ</label>
                                    <input type="text" id="date" name="date" class="form-control"
                                           placeholder="YY - MM - DD">
                                </div>
                                <div class="col mb-3">
                                    <label for="payment_mode_id" class="form-label">نحوه پرداخت</label>
                                    <select class="form-select" id="payment_mode_id" name="payment_mode_id">
                                        <option value="" selected disabled>انتخاب نحوه پرداخت</option>
                                        @foreach(\App\Models\PaymentMode::all()->pluck('display_name','id') as $id => $name)
                                            <option value="{{ $id }}"
                                                    @if($id == '4' || $id == '5') disabled @endif>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label" for="paying_amount">مقدار پرداختی</label>
                                    <div class="input-group">
                                        <input type="text" id="paying_amount" onkeyup="changeAmount(this)" name="paying_amount"
                                               class="form-control text-start">
                                        <span class="input-group-text">تومان</span>
                                    </div>
                                    <small>بیشترین مقدار پرداخت: <span id="max-paying-amount">0</span></small>
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                    بستن
                                </button>
                                <button type="button" onclick="addPay()" class="btn btn-primary">ایجاد پرداخت</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        <!--/ endregion Add Payment Modal -->
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jdate/jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/l10n/fa-jdate.js') }}"></script>
    <script>
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

        $("#date").flatpickr({
            monthSelectorType: 'static',
            locale: 'fa',
            altInput: true,
            altFormat: 'Y-m-d',
            disableMobile: true
        });

        function showDetail(input = null, saleId) {
            if (!isNaN(saleId)) {
                $.ajax({
                    type: "POST",
                    url: '{{ route('dashboard.purchase.get-detail') }}',
                    data: {
                        sale_id: saleId
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
                            $("#detail_section").html(response);
                        }
                    },
                    error: function (response) {
                        console.log(response);

                    },
                    timeout: 3000,
                });
            }
            $("#order_id").val(saleId);

            if (input != null) {
                let total = parseInt($(input).closest('tr').data('max-amount'));
                $("#total").val(total);
                $("#max-paying-amount").text(total.toLocaleString(undefined, {minimumFractionDigits: 0}));
            }
        }

        function resetAll() {
            $('form #hasEmpty :input').val('');
            setTimeout(function () {
                location.reload()
            }, 2000);
        }


        function addPay() {
            var formEl = "#addPayForm";
            $.ajax({
                type: "POST",
                url: '{{ route('dashboard.sales.payment.store') }}',
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
                    if (response.status === true) {
                        // modal close
                        $('#addPayment').modal('hide');
                        // show toast message
                        toastr.success('پرداخت با موفقیت افزوده شد.');
                        // update detail
                        //showDetail(null, $("#order_id").val());
                        //Livewire.emit('refreshSaleList');
                        resetAll();
                    }
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

        $('select[name="payment_mode_id"]').on("change", function () {
            let payModeId = $(this).val();
            if (payModeId === '2' || payModeId === '3') {
                $('#referenceIdSection').removeClass('d-none');
            } else {
                $('#referenceIdSection').addClass('d-none');
            }
        });

        function changeAmount(input) {
            let payingAmount = $(input).val() !== '' ? parseInt($(input).val()) : 0;
            let total = parseInt($('#total').val());
            if (payingAmount > total) {
                //TODO:: change to sweet alert
                alert('مبلغ پرداختی نمی‌تواند بیشتر از مبلغ فاکتور باشد.');
                $(input).val('');
                return;
            }
        }


        //change to delete-purchase
        $(".delete-purchase").on('click', function (event) {
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
                    $("#deletePurchaseConfirm-" + id).submit();
                }
            });
        });


    </script>
@endsection
