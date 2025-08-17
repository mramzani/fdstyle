<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\Product;

class AttributeController extends Controller
{

    public function edit(Product $product)
    {
        return view('product::attribute.edit', compact('product'));
    }


    public function update(Request $request, Product $product)
    {

        $validData = $request->validate([
            'attributes' => 'array',
        ]);


        $product->attributes()->detach();

        $attributes = collect($validData['attributes']);


        $this->syncAttribute($attributes, $product);


        return back()->with('alertSuccess', 'ویژگی جدید با موفقیت اضافه شد.');
    }

    private function syncAttribute($attributes, $product): void
    {
        $attributes->each(function ($item) use ($product) {

            if (!array_key_exists('name', $item) || !array_key_exists('value', $item)) return back()->with('alertWarning', 'عنوان ویژگی یا مقدار نباید خالی باشد.');

            $attr = Attribute::firstOrCreate([
                'name' => $item['name'],
            ]);
            //$attr->is_filterable = array_key_exists('filterable', $item) ? 1 : 0;
            $attr->save();


            $attr_value = $attr->values()->firstOrCreate(
                ['value' => $item['value']]
            );
            $product->attributes()->attach($attr->id, ['value_id' => $attr_value->id]);

        });

    }

    public function getValue(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required'
        ]);
        $attribute = Attribute::where('name', $validData['name'])->first();
        if (is_null($attribute)) {
            return response(['data' => []]);
        }
        return response(['data' => $attribute->values->pluck('value')]);
    }

}
