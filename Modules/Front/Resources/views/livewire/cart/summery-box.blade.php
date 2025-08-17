<div>
    @foreach(Cost::getSummery() as $description => $detail)
        @if($detail['amount'] != 0)
            <div class="d-flex justify-content-between px-3 py-2">
                <span class="{{ $detail['class'] }}">{{ $description }}</span>
                <span class="{{ $detail['class'] }}">
                    <span> {{ number_format($detail['amount']) }} </span>
                    <span class="text-sm">
                             <svg style="width: 18px; height: 18px;">
                                <use xlink:href="#toman"></use>
                             </svg>
                    </span>
                </span>
            </div>
            <hr>
        @elseif(resolve(\Modules\Dashboard\Helper\Setting\ShippingSetting::class)->shipping_free_cost != 0 and \App\Services\Cart\Facades\Cart::subTotal() >= resolve(\Modules\Dashboard\Helper\Setting\ShippingSetting::class)->shipping_free_cost)
            <div class="d-flex justify-content-between px-3 py-2">
                <span class="text-muted">هزینه ارسال</span>
                <span class="text-success">
                        <span> رایگان </span>
                </span>
            </div>
        {{--@else
            <div class="d-flex justify-content-between px-3 py-2">
                <span class="text-muted">هزینه ارسال</span>
                <span class="text-danger">
                        <span>محاسبه نشده </span>
                    </span>
            </div>--}}
        @endif
    @endforeach

    <div class="d-flex justify-content-between px-3 py-2">
        <span class="font-weight-bold">مبلغ قابل پرداخت</span>
        <span class="font-weight-bold">
            {{ number_format(Cost::getTotalCosts()) }}
            <span class="text-sm">
                <svg style="width: 18px; height: 18px;">
                    <use xlink:href="#toman"></use>
                </svg>
            </span>
        </span>
    </div>
    @if(resolve(\Modules\Dashboard\Helper\Setting\ShippingSetting::class)->shipping_free_cost != 0)
        @php
            $due = resolve(\Modules\Dashboard\Helper\Setting\ShippingSetting::class)->shipping_free_cost - \App\Services\Cart\Facades\Cart::subTotal();
        @endphp
        @if($due >= 0 and $due < 500000)
            <div class="p-2">
                <div class="alert alert-primary text-justify">
                    {{ number_format($due) }} تومان تا ارسال رایگان!
                </div>
            </div>
        @endif

    @endif
</div>
