@extends('dashboard::layouts.master')
@section('dashboardTitle',$payment_type)
@section('styles')

@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl-12">
                <h6 class="text-muted">{{ $payment_type }}</h6>
                @include('dashboard::partials.alert')

                @if($paymentType == 'in')
                    <livewire:order::payments.payments-list type="in"/>
                @elseif($paymentType == 'out')
                    <livewire:order::payments.payments-list type="out"/>
                @endif

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(".delete-payment").on('click', function (event) {
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
                    $("#deletePaymentConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
