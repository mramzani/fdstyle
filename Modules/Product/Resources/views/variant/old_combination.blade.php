@if(count($combinations[0]) > 0)
    <table class="table table-bordered sku_table">
        <thead>
        <tr>
            <td  class="text-center w-25">
                <label for=""
                       class="control-label">{{__('product::products.variant')}}</label>
            </td>
            <td  class="text-center w-25">
                <label for="" class="control-label">{{__('product::products.purchase price')}}</label>
            </td>
            <td  class="text-center w-25">
                <label for="" class="control-label">{{__('product::products.sales price')}}</label>
            </td>
            <td  class="text-center w-25">
                <label for="" class="control-label">{{__('product::products.sku')}}</label>
            </td>
            <td class="text-center w-auto">
                <label for="" class="control-label">{{__('product::products.sku')}}</label>
            </td>
        </tr>
        </thead>
        <tbody>


        @foreach ($combinations as $index => $combination)
            @php
                $str = '';
                $str_id = '';
                $attribute_id = '';
                $sku = '';

                foreach ($combination as $key => $items){

                    $item_value = \Modules\Product\Entities\AttributeValue::findOrFail($items);
                    $item = $item_value->valuable->code;
                    $name = $item_value->valuable->title;

                    if($key > 0 ){
                        $attribute_id .= '_'.str_replace(' ', '', $attributes[$key]);
                        $str_id .= '_'.str_replace(' ', '', $items);
                        $str .= '-'.str_replace(' ', '', $name);
                        $sku .= ''.str_replace(' ', '', $item);
                    }
                    else {
                        $attribute_id .= str_replace(' ', '', $attributes[$key]);
                        $str_id .= str_replace(' ', '', $items);
                        $str .= str_replace(' ', '', $name);
                        $sku .= str_replace(' ', '', $item);
                    }
                }
            $query_1 = @$product->skus->where('sku', $sku)->first();


            @endphp
            @if(strlen($str) > 0)
                <tr class="variant-tbl">
                    <td  class="text-center pt-36">
                        <input type="hidden" name="options[]" value="{{ $attribute_id }}">
                        <input type="hidden" name="values[]" value="{{ $str_id }}">
                        <input type="hidden" name="sku_name[]" value="{{ $str }}">
                        <label for="" class="control-label mt-30 w-auto">{{ $str }}</label>
                    </td>


                    <td  class="text-center pt-25">
                        <input class="form-control mt-30 w-auto" type="number" name="purchase_price[]"
                               value="{{ ($query_1) ? @$query_1->purchase_price : "0" }}" min="0" required>
                    </td>


                    <td  class="text-center pt-25 stock">
                        <input class="form-control mt-30 w-auto" type="number" name="sales_price[]"
                               value="{{ ($query_1) ? @$query_1->sales_price : "0" }}" min="0"
                               required>
                    </td>

                    <td  class="text-center pt-25 stock">
                        <input class="form-control mt-30 w-auto" type="text" name="sku[]"
                               value="{{ ($query_1) ? @$query_1->sku : $sku }}" readonly
                               required>
                    </td>
                    <td  class="text-center pt-25 stock w-auto">
                        <a class="btn cursor_pointer variation_remove"><i
                                class="bx bx-trash"></i></a>
                    </td>

                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
@endif
