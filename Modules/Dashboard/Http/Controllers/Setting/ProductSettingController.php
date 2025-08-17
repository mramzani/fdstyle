<?php

namespace Modules\Dashboard\Http\Controllers\Setting;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Entities\Guarantee;
use Modules\Dashboard\Helper\Setting\ProductSettings;
use Modules\Product\Entities\Product;

class ProductSettingController extends Controller
{

    public function edit(ProductSettings $productSettings)
    {
        return view('dashboard::settings.product', [
            'carousel_pagination_number' => $productSettings->carousel_pagination_number,
            'product_prefix' => $productSettings->product_prefix,
            'tax_is_active' => $productSettings->tax_is_active,
            'preparation_time' => $productSettings->preparation_time,
            'display_product_without_image' => $productSettings->display_product_without_image,
            'guarantee_id' => $productSettings->default_guarantee_id,
        ]);
    }


    public function update(Request $request, ProductSettings $productSettings)
    {
        //validation
        $request->validate([
            'carousel_pagination_number' => 'int|required',
            'product_prefix' => 'string|required',
            'tax_is_active' => 'nullable|bool',
            'preparation_time' => 'int|required',
            'display_product_without_image' => 'bool|nullable',
            'guarantee_id' => 'required',
        ]);
        try {
            //update
            $productSettings->carousel_pagination_number = $request->input('carousel_pagination_number');
            $productSettings->product_prefix = $request->input('product_prefix');
            $productSettings->tax_is_active = $request->has('tax_is_active') ? $request->input('tax_is_active') : false;
            $productSettings->display_product_without_image = $request->has('display_product_without_image') ? $request->input('display_product_without_image') : false;
            $productSettings->preparation_time = $request->input('preparation_time');
            $productSettings->default_guarantee_id = $request->input('guarantee_id');
            $productSettings->save();

            $this->changeAllGuarantee($productSettings->default_guarantee_id);
            //redirect back and show message
            return back()->with('alertSuccess', 'تنظیمات محصول با موفقیت بروزرسانی شد.');
        } catch (\Exception $exception) {
            return back()->with('alertWarning', 'خطا در بروزرسانی' . $exception->getMessage());
        }

    }

    private function changeAllGuarantee(mixed $default_guarantee_id)
    {
        foreach (Product::query()->where('guarantee_id', null)->get() as $product) {
            $product->guarantee_id = $default_guarantee_id;
            $product->save();
        }
    }

}
