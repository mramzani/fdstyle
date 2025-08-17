@extends('front::layouts.app')
@section('title','آدرس تحویل سفارش و پرداخت')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/spinkit/spinkit.css') }}"/>
@endsection
@section('mainContent')
    <!-- Page Content -->

    <main class="page-content">
        <div class="container">
            <div class="row mb-4">
                <div class="col-xl-9 col-lg-8 col-md-8 mb-md-0 mb-3">
                    <!-- address section -->
                    <div class="checkout-section shadow-around">
                        <div class="checkout-section-content">
                            <div class="checkout-section-title">آدرس تحویل سفارش</div>
                            @livewire('front::address.address-list')
                        </div>
                    </div>

                    <!-- coupon section -->
                    @include('coupon::coupon.coupon-box')
                    <!-- payment method section -->
                    <div class="checkout-section shadow-around my-2">
                        <div class="checkout-section-content">
                            <div class="checkout-section-title">انتخاب روش پرداخت</div>
                            <div class="payment-selection">
                                <div class="custom-control custom-radio custom-control-inline mb-3">
                                    <input type="radio" id="paymentSelection1" name="paymentSelection"
                                           class="custom-control-input" checked>
                                    <label class="custom-control-label payment-select" for="paymentSelection1">
                                            <span class="d-flex align-items-center">
                                                <i class="fad fa-credit-card"></i>
                                                <span>
                                                    <span class="title">پرداخت اینترنتی</span>
                                                    <span class="subtitle">پرداخت از طریق کارت‌ بانکی</span>
                                                </span>
                                            </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- summery section -->
                    <div class="d-none checkout-section shadow-around my-2">
                        <div class="checkout-section-content">
                            <div class="row mx-0">
                                <div class="col-12">
                                    <div class="checkout-section-title">خلاصه سفارش</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-3 col-lg-4 col-md-4">
                    <div class="shadow-around pt-3">
                        @livewire('front::cart.summery-box')


                        @livewire('front::cart.submit-form')


                    </div>
                    <div class="my-2 px-2">
                        <p class="text-muted">هزینه این سفارش هنوز پرداخت نشده‌ و در صورت اتمام موجودی، کالاها از سبد
                            حذف می‌شوند</p>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <!-- end Page Content -->

    <!-- addAddressModal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">آدرس جدید</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @livewire('front::address.add-address')
                </div>

            </div>
        </div>
    </div>
    <!-- end addAddressModal -->
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script type="text/javascript">
        window.addEventListener('close-modal', event => {
            $("#addAddressModal").modal('hide');
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            Toast.fire({
                icon: 'success',
                title: 'آدرس جدید با موفقیت افزوده شد'
            });
        });

        $(".summeryLoading").block({
            message:
                '<div class="d-flex align-items-center"></div>',
            timeout: 5000,
            css: {
                backgroundColor: 'transparent',
                color: '#fff',
                border: '0'
            },
            overlayCSS: {
                opacity: 0.7
            }
        });

    </script>
@endsection
