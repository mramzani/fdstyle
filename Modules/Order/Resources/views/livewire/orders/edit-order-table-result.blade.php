<div>
    @error('duplicate_product')
    <div class="alert alert-warning">{{ $message }}</div>
    @enderror

    @error('not_allowed_add_product_to_factor')
    <div class="alert alert-warning">{{ $message }}</div>
    @enderror
    <div class="table-responsive text-nowrap">
        @if($products->count() == 0)
            <div class="alert alert-info zindex-1">محصولی را اضافه کنید</div>
        @else
            <table class="table table-hover text-center" id="orderList">
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
                <tbody class="table-border-bottom-0">

                @foreach($products as $key => $product)
                    <tr wire:key="{{ $product['id'] }}"
                            data-stock="{{ isset($product['detail'])
                                    ? $product['detail']['current_stock']
                                    : $product['current_stock'] }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>
                            {{ $product['name'] }}
                            <small>
                                <span class="badge bg-label-warning">موجودی:
                                    {{ isset($product['detail'])
                                    ? $product['detail']['current_stock']
                                    : $product['current_stock'] }} عدد
                                </span>
                            </small>
                        </td>
                        <td>
                            {{ $product['barcode'] ?? $product['code'] }}
                        </td>
                        <td>
                            <input type="text"
                                   class="form-control w-px-50 quantity"
                                   onkeyup="qtyChange(this,{{ $loop->index }})"
                                   value="{{ $product->pivot->quantity ?? 1 }}"
                                   id="quantity_{{ $loop->index }}"
                                   name="products[{{ $loop->index }}][quantity]">
                        </td>
                        <td>
                            @if(isset($product['pivot']))
                                <span>{{ number_format($product['pivot']['unit_price']) }}</span>
                                <input type="hidden" name="products[{{ $loop->index }}][unit_price]"
                                       id="unit_price_{{ $loop->index }}" value="{{ $product['pivot']['unit_price'] }}"
                            @else
                                {{ number_format($product['unit_price']) }}
                                <input type="hidden" name="products[{{ $loop->index }}][unit_price]"
                                       id="unit_price_{{ $loop->index }}" value="{{ $product['unit_price'] }}">
                            @endif
                        </td>
                        <td>
                            @if(isset($product['pivot']))
                                <span id="row_price_{{ $loop->index }}" >{{ number_format($product['pivot']['subtotal']) }}</span>
                                <input type="hidden" name="products[{{ $loop->index }}][subtotal]"
                                       id="subtotal_{{ $loop->index }}" class="subtotal" value="{{ $product['pivot']['subtotal'] }}">
                            @else
                                <span id="row_price_{{ $loop->index }}">{{ number_format($product['unit_price']) }}</span>
                                <input type="hidden" name="products[{{ $loop->index }}][subtotal]"
                                       id="subtotal_{{ $loop->index }}" class="subtotal" value="{{ $product['unit_price'] }}">
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-icon" onclick="deleteProductRow(this)" wire:click="deleteRow({{ $loop->index }})"><i
                                    class="bx bx-trash"></i>
                            </button>
                        </td>

                        <input type="hidden" value="" name="products[{{$loop->index}}][item_id]"
                               id="item_id_{{ $loop->index }}">
                        <input type="hidden" value="{{ $product['id'] }}" name="products[{{ $loop->index }}][product_id]">
                        <input type="hidden" value="{{ $product['variant_id'] ?? null }}"
                               name="products[{{ $loop->index }}][variant_id]">
                        <input type="hidden" value="{{ $product['barcode'] ?? $product['code'] }}"
                               name="products[{{ $loop->index }}][code]">
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="row" id="summery-box" wire:ignore>
                <div id="inputHiddenSection">
                    {{--<input type="hidden" id="discount" name="discount" value="0">--}}
                    <input type="hidden" id="subtotal" name="subtotal" value="{{ $sale->subtotal }}">
                    <input type="hidden" id="total" name="total" value="{{ $sale->total }}">
                    <input type="hidden" id="paid_amount" name="paid_amount" value="{{ $sale->paid_amount }}">
                    <input type="hidden" id="due_amount" name="due_amount" value="{{ $sale->due_amount }}">
                </div>
                <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-text" dir="ltr">تعداد اقلام</span>
                            <input type="text" name="total_items" id="total_items" class="form-control"
                                   value="{{ $sale->total_items }}" readonly/>
                            <span class="input-group-text" dir="ltr">تعداد کل</span>
                            <input type="text" name="total_quantity" id="total_quantity" class="form-control"
                                   value="{{ $sale->total_quantity }}"
                                   readonly/>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-text col-3" dir="ltr">مجموع</span>
                            <span class="input-group-text col-6" id="subtotal_label">{{ number_format($sale->subtotal) }}</span>
                            <span class="input-group-text col-3">تومان</span>
                        </div>
                    </fieldset>
                </div>
                <div class="col-12 col-sm-12 col-lg-12 col-xl-4 mb-1">
                    <fieldset>
                        <div class="input-group">
                            <span class="input-group-text" dir="ltr">تخفیف</span>
                            <input type="number" id="discount" name="discount" class="form-control" value="{{ $sale->discount }}"
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
                            <input type="number" name="shipping" id="shipping" value="{{ $sale->shipping }}"
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
                    <span id="total_label" class="text-success bold">{{ number_format($sale->total) }}</span>
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
        <button type="submit" class="btn btn-primary d-grid w-100" >
            <span class="d-flex align-items-center justify-content-center text-nowrap">ذخیره</span>
        </button>
    </div>

    @once
        <script>
            /*function qtyChange(input,rowIndex) {
                let unit_price = $(input).closest('tr').find('#unit_price_' + rowIndex).val();
                let quantity = $(input).closest('tr').find('#quantity_' + rowIndex).val() || 1;

            }*/
        </script>
    @endonce
</div>
