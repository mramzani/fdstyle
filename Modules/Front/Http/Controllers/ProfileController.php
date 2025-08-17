<?php

namespace Modules\Front\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Entities\City;
use Modules\User\Helper\Helper;

class ProfileController extends BaseController
{
    public function index()
    {
        return view('front::profile.index');
    }

    public function personalInfoFrom()
    {
        return view('front::profile.personal-info');
    }

    public function savePersonalInfo(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'email' => 'nullable|email',
            'national_code' => 'string|nullable|ir_national_code',
        ]);

        $customer = Helper::getCustomer();

        $customer->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'national_code' => $request->input('national_code'),
            'status' => 'active'
        ]);
        $customer->save();

        $request->session()->flash('alertSuccess', 'حساب کاربری با موفقیت بروزرسانی شد.');
        return redirect()->intended();
    }

    public function addressList()
    {
        return view('front::address.list');
    }

    public function getCity($provinceId)
    {

        $cities = City::query()->where('province_id', $provinceId)->get();

        return response([
            'cities' => $cities
        ]);
    }
}
