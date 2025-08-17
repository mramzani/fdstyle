<?php

namespace Modules\Front\Http\Controllers;

use App\Services\Cart\Facades\Cart;
use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Otp;
use Modules\User\Services\Auth\Customer\Authentication;
use Throwable;

class AuthController extends BaseController
{
    protected Authentication $auth;
    private Request $request;


    public function __construct(Request $request, Authentication $auth)
    {
        $this->request = $request;
        $this->auth = $auth;
    }


    public function showLoginForm(): View
    {

        if ($this->request->get('utm_campaign') == "discount_first_buy"){
            $this->request->session()->flash('alertSuccess','بعد ثبت نام کدتخفیف بصورت خودکار برای شما ارسال خواهد شد.');
        }

        if ($this->request->has('backUrl')){
            redirect()->setIntendedUrl($this->request->get('backUrl'));
        }



        session()->remove('code_id');

        return view('front::auth.login');
    }


    /**
     * @throws Throwable
     */
    public function requestOtp()
    {
        $this->validateRequestOtp();

        $response = $this->auth->requestCode($this->request->input('mobile'));

        return $response == $this->auth::CODE_SEND
            ? redirect()->route('front.user.confirm.form')
            : back()->withErrors(['request_otp_error' => 'خطایی در ورود رخ داده است.']);

    }

    public function confirmForm()
    {
        if (session()->exists('code_id') and Otp::where('id', session()->get('code_id'))->exists())
            return view('front::auth.confirm');
        else
            return redirect()->route('front.user.login');
    }

    public function confirmCode()
    {
        $this->validateConfirmCode();

        $code = Common::toEnglishNumber($this->request->input('code'));

        $response = $this->auth->checkCode($code);


        if ($response == $this->auth::INVALID_CODE){
            return back()->withErrors(['invalid_code' => 'کد وارد شده صحیح نمی‌باشد ']);
        } else {
            $this->storeCart();

            return redirect()->intended();
        }
    }

    public function logout()
    {

        auth()->guard('customer')->logout();

        //$this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        return redirect()->route('front.home');

    }

    /**
     * Validation Request Otp Code
     * @return void
     */
    private function validateRequestOtp()
    {
        $this->request->validate([
            'mobile' => ['required', 'numeric', 'alpha_num', 'digits:11']
        ]);
    }

    /**
     * validation confirm code input
     * @return void
     */
    private function validateConfirmCode(): void
    {
        $this->request->validate([
            'code' => ['required', 'numeric', 'digits: ' . config('user.otp.code_length')]
        ]);
    }

    private function storeCart():void
    {
        if (Cart::instance('shopping')->exists()){
            Cart::instance('shopping')->store();
        }
    }
}
