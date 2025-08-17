<div>
    @error('duplicate_product')
    <div class="alert alert-warning">{{ $message }}</div>
    @enderror

    @error('not_allowed_add_product_to_factor')
    <div class="alert alert-warning">{{ $message }}</div>
    @enderror
    <div class="table-responsive text-nowrap">
        @if($products->count() == 0)
            <div class="alert alert-info">محصولی را اضافه کنید</div>
        @else
            <table class="table table-striped table-responsive" id="orderList">
                <thead>
                <tr>
                    <th class="w-px-20">#</th>
                    <th class="w-auto">نام محصول</th>
                    <th class="w-px-100">تعداد</th>
                    <th class="w-px-150">قیمت کل</th>
                    <th class="w-px-20">عملیات</th>
                </tr>
                </thead>
                <tbody class="mt-0">
                @foreach($products as $product)
                    <tr wire:key="{{ $product['id'] }}"
                        data-stock="{{ $product['quantity'] }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $product['name'] }} <small><span
                                    class="badge bg-label-warning">موجودی: {{ $product['quantity'] . ' عدد' }} </span></small>
                        </td>
                        <td>

                            <input wire:ignore onkeyup="qtyChange(this,{{ $loop->index }})" type="text"
                                   id="quantity_{{ $loop->index }}"
                                   class="form-control form-control-sm w-px-50 quantity"
                                   name="products[{{$loop->index}}][quantity]"
                                   value="1">
                        </td>
                        <td>
                                <span wire:ignore
                                      id="row_price_{{ $loop->index }}">{{ number_format($product['sales_price']) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="#" class="text-body" onclick="deleteProductRow(this)"
                                   wire:click="deleteRow({{ $loop->index }})">
                                    <i class="bx bx-trash mx-1"></i></a>
                                <a href="#" class="text-body">
                                    <i class="bx bx-edit mx-1"></i></a>
                            </div>
                        </td>
                        <input type="hidden" value="{{ $product['sales_price'] }}"
                               name="products[{{$loop->index}}][unit_price]" id="unit_price_{{ $loop->index }}">
                        <input type="hidden" value="{{ $product['id'] }}" name="products[{{$loop->index}}][product_id]">
                        <input type="hidden" value="{{ $product['variant_id'] ?? null }}"
                               name="products[{{$loop->index}}][variant_id]">
                        <input type="hidden" value="" name="products[{{$loop->index}}][item_id]"
                               id="item_id_{{ $loop->index }}">
                        {{-- <input type="hidden" value="0" name="products[{{$loop->index}}][discount_rate]"
                                id="discount_rate_{{ $loop->index }}">
                         <input type="hidden" value="0" name="products[{{$loop->index}}][total_discount]"
                                id="total_discount_{{ $loop->index }}">--}}
                        <input type="hidden" value="{{ $product['sales_price'] }}" class="subtotal" wire:ignore
                               name="products[{{$loop->index}}][subtotal]" id="subtotal_{{ $loop->index }}">

                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>

    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="row" id="summery-box">

                <div id="inputHiddenSection">
                    {{--<input type="hidden" id="discount" name="discount" value="0">--}}
                    <input type="hidden" id="subtotal" name="subtotal" value="0">
                    <input type="hidden" id="total" name="total" value="0">
                    <input type="hidden" id="paid_amount" name="paid_amount" value="0">
                    <input type="hidden" id="due_amount" name="due_amount" value="0">
                </div>

                <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-text" dir="ltr">تعداد اقلام</span>
                            <input type="text" name="total_items" id="total_items" class="form-control"
                                   value="0" readonly/>
                            <span class="input-group-text" dir="ltr">تعداد کل</span>
                            <input type="text" name="total_quantity" id="total_quantity" class="form-control"
                                   value="0"
                                   readonly/>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-text col-3" dir="ltr">مجموع</span>
                            <span class="input-group-text col-6" id="subtotal_label">0</span>
                            <span class="input-group-text col-3">تومان</span>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-text" dir="ltr">تخفیف</span>
                            <input type="number" id="discount" name="discount" class="form-control" value="0"
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
                            <select {{--name="tax_amount"--}} class="form-control" id="tax" readonly="">
                                <option value="0" selected>بدون مالیات</option>
                            </select>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-text" dir="ltr">حمل و نقل</span>
                            <input type="number" name="shipping" id="shipping" value="0"
                                   class="form-control"
                                   placeholder="هزینه حمل و نقل">
                            <button type="button" onclick="applyShipping()" class="btn btn-primary"
                                    id="shipping-btn">
                                اعمال
                            </button>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="w-100 text-center bg-label-success p-1 my-2">
                <h4 class="mb-0 text-white" >جمع کل:
                    <span id="total_label" class="text-success bold">0</span>
                    <span class="text-white">تومان</span>
                </h4>
            </div>
        </div>
        <div>
            <audio id="beep-timber" preload="auto">
                <source src="{{ url('assets/panel/audio/beep-timber.mp3') }}" type="audio/mpeg"/>
            </audio>
            <audio id="beep" preload="auto">
                <source src="{{url('assets/panel/audio/beep-07.mp3')}}" type="audio/mpeg"/>
            </audio>
        </div>
    </div>
    <div class="col-12 my-1" wire:ignore>
        <button type="button" id="PaymentBtn" disabled class="btn btn-primary d-grid w-100" data-bs-toggle="offcanvas"
                data-bs-target="#addPayment">
            <span class="d-flex align-items-center justify-content-center text-nowrap">پرداخت (Alt + P)</span>
        </button>
    </div>
    <!-- Add Payment Sidebar -->
    <div class="offcanvas offcanvas-end" id="addPayment" aria-hidden="true">
        <div class="offcanvas-header border-bottom">
            <h6 class="offcanvas-title">افزودن پرداخت</h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
                    <input type="text" id="paying_amount" onkeyup="changeAmount(this)" name="paying_amount" value="0"
                           class="form-control text-start paying_amount">
                    <span class="input-group-text">تومان</span>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="payment_mode_id">نحوه پرداخت</label>
                <select class="form-select" id="payment_mode_id" name="payment_mode_id">
                    <option value="" selected disabled>انتخاب نحوه پرداخت</option>
                    @foreach(\App\Models\PaymentMode::all()->pluck('display_name','id') as $id => $name)
                        <option value="{{ $id }}" @if($id == '4' || $id == '5') disabled @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 d-none" id="referenceIdSection">
                <label class="form-label" for="reference_id">پیگیری پرداخت</label>
                <input type="text" id="reference_id" name="reference_id" placeholder="شماره پیگیری پرداخت"
                       class="form-control text-start">
            </div>
            <div class="mb-4">
                <label class="form-label" for="payment-note">یادداشت پرداخت</label>
                <textarea class="form-control" name="notes" id="notes" rows="2"></textarea>
            </div>
            <div class="mb-3 d-flex flex-wrap">
                <button type="button" id="saveSale" class="btn btn-primary me-3">ارسال</button>
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">انصراف</button>
            </div>
        </div>
    </div>
    <!-- /Add Payment Sidebar -->
    @once
        <script>


        </script>
    @endonce
</div>
