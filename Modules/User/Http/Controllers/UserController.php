<?php

namespace Modules\User\Http\Controllers;


use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Customer;
use Modules\User\Entities\User;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('user::users.list');
    }

    /**
     * Show the specified resource.
     * @param Customer $customer
     * @return Renderable
     */
    public function show(Customer $customer)
    {
        $customer->load('detail', 'roles');

        //return view('user::show',compact('customer'));
    }


    public function profile()
    {

        $user = $this->user();

        return view('user::profile.index', compact('user'));
    }

    public function profileSave(Request $request)
    {
        $request->input('status') == 'on'
            ? $request->merge(['status' => 'active'])
            : $request->merge(['status' => 'deActive']);


        $validData = $request->validate([
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'national_code' => 'string|nullable|ir_national_code',
            'email' => 'required|email',
            //'mobile' => 'required|ir_mobile:zero|unique:users,mobile,'.$this->user()->id,
            'status' => 'string|in:' . collect(User::STATUSES)->keys()->implode(','),
        ]);

        $this->user()->update([
            'first_name' => $validData['first_name'],
            'last_name' => $validData['last_name'],
            'national_code' => $validData['national_code'],
            'email' => $validData['email'],
            //'status' => $request->has('status')  ? 'active' : 'deActive',
        ]);

        session()->forget('user');

        return Common::redirectTo('dashboard.user.profile', 'alertSuccess', trans('dashboard.setting.general.The operation was successful'));
    }

    private function user()
    {
        return \Auth::user();
    }
}
