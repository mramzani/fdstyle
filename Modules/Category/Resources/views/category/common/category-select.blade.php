@for($i=0;$i<count($categories);$i++)
    <option
        @if(Route::is('products.edit') or Route::is('products.create') or Route::is('variants.index') or Route::is('attribute-group.edit'))
            @if(!empty($categories[$i]['child'])) disabled @endif
        @endif

    value="{{ $categories[$i]['id'] }}"
    @if(Route::is('categories.edit'))
        {{ $categories[$i]['id'] == $category->parent_id ? 'selected' : '' }}
    @endif
    @if(Route::is('products.edit'))
        {{ $categories[$i]['id'] == $product->category_id ? 'selected' : '' }}
    @endif
    @if(Route::is('attribute-group.edit'))
        {{ $categories[$i]['id'] == $attribute_group->category_id ? 'selected' : '' }}
    @endif
    >{!! str_repeat('&larr;',$level) !!}{{ $categories[$i]['title_fa'] }}</option>
    @if(!empty($categories[$i]['child']))
        @include('category::category.common.category-select',['categories' => $categories[$i]['child'] ,'level' => $level+1 ])
    @endif
@endfor
