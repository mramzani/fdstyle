<?php

namespace Modules\Coupon\Http\Controllers\Panel;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Helper\Settings\CouponSetting;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $coupons = Coupon::query()->paginate(15);

        return view('coupon::panel.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('coupon::panel.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request)
    {

        \DB::beginTransaction();
        try {
            //validation
            $request->validate([
                'code' => 'string|required|unique:coupons,code',
                'percent' => 'integer|required',
                'limit' => 'integer|required',
                'min_basket_amount' => 'integer|required',
                'allowed_qty' => 'integer|required',
                'expire_date' => 'required',
            ]);

            //create coupon
            Coupon::create([
                'code' => $request->input('code'),
                'percent' => $request->input('percent'),
                'limit' => $request->input('limit'),
                'min_basket_amount' => $request->input('min_basket_amount'),
                'allowed_qty' => $request->input('allowed_qty'),
                'expire_date' => Common::convertToJalali($request->input('expire_date')),
                'status' => $request->input('status') == "enable" ? "enable" : "disable",
            ]);

            \DB::commit();

            return Common::redirectTo('dashboard.coupons.index', 'alertSuccess', 'کد تخفیف با موفقیت ایجاد شد.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.coupons.index', 'alertWarning', 'خطا در ایجاد کدتخفیف');
        }
    }


    /**
     * Show the form for editing the specified resource.
     * @param Coupon $coupon
     * @return Renderable
     */
    public function edit(Coupon $coupon)
    {
        return view('coupon::panel.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Coupon $coupon
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request, Coupon $coupon)
    {
        \DB::beginTransaction();
        try {
            $request->validate([
                'code' => 'string|required|unique:coupons,code,' . $coupon->id,
                'percent' => 'integer|required',
                'limit' => 'integer|required',
                'min_basket_amount' => 'integer|required',
                'allowed_qty' => 'integer|required',
                'expire_date' => 'required',
            ]);

            $coupon->update([
                'code' => $request->input('code'),
                'percent' => $request->input('percent'),
                'limit' => $request->input('limit'),
                'min_basket_amount' => $request->input('min_basket_amount'),
                'allowed_qty' => $request->input('allowed_qty'),
                'expire_date' => Common::convertToJalali($request->input('expire_date')),
                'status' => $request->input('status') == "enable" ? "enable" : "disable",
            ]);

            \DB::commit();
            return Common::redirectTo('dashboard.coupons.index', 'alertSuccess', 'کوپن تخفیف با موفقیت ویرایش شد');
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.coupons.index', 'alertWarning', 'خطا در ویرایش کوپن تخفیف');

        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Coupon $coupon
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function destroy(Coupon $coupon)
    {
        \DB::beginTransaction();
        try {
            $coupon->delete();
            \DB::commit();
            return Common::redirectTo('dashboard.coupons.index', 'alertSuccess', 'کوپن تخفیف حذف شد.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.coupons.index', 'alertWarning', 'خطا در حذف کوپن تخفیف');
        }

    }

    public function setting(CouponSetting $couponSetting)
    {
        return view('coupon::panel.setting',[
            'status' => $couponSetting->status,
            'coupon_prefix' => $couponSetting->coupon_prefix,
            'coupon_percent' => $couponSetting->coupon_percent,
            'coupon_limit' => $couponSetting->coupon_limit,
            'min_basket_amount' => $couponSetting->min_basket_amount,
            'allowed_qty' => $couponSetting->allowed_qty,
            'deadline' => $couponSetting->deadline,
        ]);
    }

    public function updateSetting(CouponSetting $couponSetting,Request $request)
    {

        $request->validate([
            'coupon_prefix' => 'required',
            'coupon_percent' => 'required',
            'coupon_limit' => 'required',
            'min_basket_amount' => 'required',
            'allowed_qty' => 'required',
            'deadline' => 'required',
        ]);

        $couponSetting->status = $request->input('status') == "enable";
        $couponSetting->coupon_prefix = $request->input('coupon_prefix');
        $couponSetting->coupon_percent = $request->input('coupon_percent');
        $couponSetting->coupon_limit = $request->input('coupon_limit');
        $couponSetting->min_basket_amount = $request->input('min_basket_amount');
        $couponSetting->allowed_qty = (int)$request->input('allowed_qty');
        $couponSetting->deadline = $request->input('deadline');
        $couponSetting->save();

        return Common::redirectTo('dashboard.coupons.index','alertSuccess','تنظیمات باموفقیت بروزرسانی شد.');
    }
}
