<?php

namespace Modules\User\Services\Auth\Customer;

use App\Services\Common;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\RedirectResponse;
use Modules\User\Entities\Customer;
use Modules\User\Entities\Otp;
use Modules\User\Events\LoginNewCustomer;
use Modules\User\Events\OtpCreated;
use Modules\User\Jobs\SendSms;
use Modules\User\Services\Notifications\Notifications;
use Throwable;

class Authentication
{
    const CODE_SEND = 'code.send';
    const INVALID_CODE = 'invalid.code';
    private Otp $code;

    /**
     * @throws Throwable
     */
    public function requestCode(string $mobile)
    {
        $mobile = Common::toEnglishNumber($mobile);

        \DB::beginTransaction();
        try {
            Customer::existsCustomer($mobile)
                ? $customer = Customer::where('mobile', $mobile)->first()
                : $customer = Customer::create([
                'mobile' => $mobile,
                'register_type' => 'online_shop',
                'user_type' => 'customer',
                'status' => 'deActive',
            ]);

            if ($customer == null) {
                throw new \InvalidArgumentException('شما مجاز به ورود نیستید');
            }

            $otp = Otp::new()->requestCode($customer);

            $this->setSession($otp);

            //$otp->sendCode();

            event(new OtpCreated($otp));

            \DB::commit();

            return static::CODE_SEND;

        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return back()->withErrors(['request_otp_error' => $exception->getMessage()]);
        }
    }

    /**
     * @param string $code
     * @return string|void
     */
    public function checkCode(string $code)
    {
        if (!$this->isValidCode($code)) return static::INVALID_CODE;

        $this->getToken()->delete();

        // customer login

        $customer = $this->getCustomer();
        auth('customer')->login($customer);

        if (coupon_setting()->status && $customer->status == "deActive"){
            event(new LoginNewCustomer($customer));
        }
        // delete all session
        $this->forgetSession();

    }

    private function setSession(Otp $otp)
    {
        session([
            'code_id' => $otp->id,
            'customer_id' => $otp->customer->id,
            'mobile' => $otp->customer->mobile
        ]);
    }

    private function isValidCode(string $code): bool
    {
        if (is_null(session('code_id'))) return false;

        return !$this->getToken()->isExpired() && $this->getToken()->isEqualWith($code);
    }

    private function getToken(): Otp
    {
        return $this->code ?? $this->code = Otp::findOrFail(session('code_id'));
    }

    private function getCustomer()
    {
        return Customer::findOrFail(session('customer_id'));
    }

    private function forgetSession()
    {
        session()->forget([
            'code_id',
            'customer_id',
            'mobile'
        ]);
    }


}
