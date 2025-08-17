@for($i=0;$i<count($categories);$i++)
    <option
        @if(\Route::is('products.edit') or \Route::is('products.create'))
            @if(!empty($categories[$i]['child'])) disabled @endif
        @endif
    value="{{ $categories[$i]['id'] }}"
    @if(Route::is('products.edit'))
        @foreach($product->categories as $category)
            {{ $categories[$i]['id'] == $category->pivot->category_id ? 'selected' : '' }}
            @endforeach
    @endif

    >{!! str_repeat('&larr;',$level) !!}{{ $categories[$i]['title_fa'] }}</option>
    @if(!empty($categories[$i]['child']))
        @include('product::product.secondary_category',['categories' => $categories[$i]['child'] ,'level' => $level+1 ])
    @endif
@endfor
