<div class="checkout-section shadow-around my-2">
    <div class="checkout-section-content">
        <div class="row mx-0">
            <div class="col-md-6">

                <div class="checkout-section-title">کدتخفیف داری؟ اینجا وارد کن</div>
                @include('front::partials.alert')

                @if(! \Session::has('coupon'))
                    <form action="{{ route('front.coupon.apply') }}" method="post">
                        @csrf
                        <div class="d-flex align-items-center">
                            <div class="form-element-row flex-grow-1">
                                <input type="text" class="input-element" name="code" id="code"
                                       placeholder="کد تخفیف را وارد کنید">
                                @error('code')
                                @include('front::partials.validation')
                                @enderror
                            </div>

                            <div class="form-element-row mr-3">
                                <button class="btn-element btn-info-element">
                                    <i class="fad fa-sync"></i>
                                    ثبت
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <form action="{{ route('front.coupon.forget') }}" method="post">
                        @csrf
                        <div class="d-flex align-items-center">
                            <div class="form-element-row">
                                <button type="button" class="btn btn-primary">
                                    @php $coupon = \Modules\Coupon\Entities\Coupon::find(\Session::get('coupon'))->first(); @endphp
                                    {{ $coupon->code }} <span class="badge badge-light">{{ $coupon->percent }}%</span>
                                </button>
                            </div>
                            <div class="form-element-row mr-2 ">
                                <button class="btn btn-info">
                                    حذف تخفیف
                                </button>
                            </div>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
