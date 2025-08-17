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
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="w-25">نام محصول</th>
                    <th class="w-25">کد محصول</th>
                    <th class="w-25">تعداد</th>
                    <th class="w-25">نوع</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                @foreach($products as $key => $product)
                    <tr>
                        <td>
                            {{ $product['name'] }}
                            <input type="hidden" value="{{ $product['id'] }}" name="products[{{$key}}][product_id]">
                            <input type="hidden" value="{{ $product['variant_id'] ?? null }}" name="products[{{$key}}][variant_id]">
                        </td>
                        <td>
                            {{ $product['code'] }}
                            <input type="hidden" value="{{ $product['code'] }}" name="products[{{$key}}][code]">
                        </td>
                        <td><input type="text" class="form-control w-auto" value="1" name="products[{{$key}}][quantity]"></td>
                        <td>
                            <select class="form-control w-auto" name="products[{{$key}}][action]">
                                <option value="add">افزودن</option>
                                <option value="subtract">کاهش</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-icon" wire:click="deleteRow({{ $key }})"><i
                                    class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
