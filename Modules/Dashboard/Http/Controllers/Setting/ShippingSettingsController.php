<?php

namespace Modules\Dashboard\Http\Controllers\Setting;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Helper\Setting\ShippingSetting;

class ShippingSettingsController extends Controller
{

    public function edit(ShippingSetting $shippingSetting)
    {
        return view('dashboard::settings.shipping',[
            'shipping_cost' => $shippingSetting->shipping_cost,
            'shipping_free_cost' => $shippingSetting->shipping_free_cost,
        ]);
    }


    public function update(Request $request,ShippingSetting $shippingSetting)
    {
        //validation
        $request->validate([
            'shipping_cost' => 'required|int',
            'shipping_free_cost' => 'required|int',
        ]);
        $shippingSetting->shipping_cost = $request->input('shipping_cost');
        $shippingSetting->shipping_free_cost = $request->input('shipping_free_cost');
        $shippingSetting->save();

        return back()->with('alertSuccess','تنظیمات باموفقیت بروزرسانی شد.');
    }


}
