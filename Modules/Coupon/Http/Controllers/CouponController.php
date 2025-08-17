<?php

namespace Modules\Coupon\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\CouponValidator;

class CouponController extends Controller
{

    private CouponValidator $validator;
    private Request $request;

    public function __construct(CouponValidator $validator,Request $request)
    {
        $this->validator = $validator;
        $this->request = $request;
    }


    public function apply()
    {
        $this->request->validate([
            'code' => ['required','exists:coupons,code']
        ]);


        try {
            $coupon = Coupon::where('code',$this->request->input('code'))->firstOrFail();
            $this->validator->isValid($coupon);
            $this->request->session()->push('coupon',$coupon->id);
            $this->request->session()->flash('alertSuccess','کد تخفیف بر روی سبد خرید شما اعمال شد.');
        }catch (\Exception $exception){
            $this->request->session()->flash('alertWarning',$exception->getMessage());
        }

        return back();
    }

    public function forget()
    {
        session()->forget('coupon');

        return back();
    }

}
