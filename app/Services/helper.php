<?php

use Illuminate\Support\Facades\Http;
use Modules\Dashboard\Entities\Company;
use Modules\Dashboard\Helper\Setting\ThirdPartySettings;
use Modules\User\Entities\User;

if (!function_exists('user')) {
    function user(): ?User
    {
        if (session()->has('user')) {
            return session('user');
        }
        $user = auth('admin')->user();

        if ($user) {
            $user = $user->load('roles', 'detail');
            session(['user' => $user]);
            return session('user');
        }

        return null;
    }
}

if (!function_exists('company')) {
    function company()
    {
        if (session()->has('company')) {
            session()->remove('company');
        }
        $company = Company::with('warehouse', 'unit')->first();

        if ($company) {
            session(['company' => $company]);
            return session('company');
        }

        return null;
    }
}

if (!function_exists('unit')) {
    function unit()
    {
        if (session()->has('unit')) {
            return session('unit');
        }
        $company = Company::with('unit')->first();

        if ($company) {
            session(['unit' => $company->unit->id]);
            return session('unit');
        }

        return null;
    }
}

if (!function_exists('warehouse')) {
    function warehouse()
    {
        if (user()) {
            $userDetail = user()->detail;
            if ($userDetail) {
                return $userDetail->warehouse;
            }
        }
        return null;
    }
}

if (!function_exists('randomNumber')) {
    function randomNumber($length = 20, $int = false): int
    {
        $numbers = "0123456789";

        $number = '';

        for ($i = 1; $i <= $length; $i++) {
            if ($i == 1) {
                $num = $numbers[rand(1, strlen($numbers) - 1)];
            } else {
                $num = $numbers[rand(0, strlen($numbers) - 1)];
            }

            $number .= $num;
        }

        if ($int) {
            return (integer)$number;
        }

        return (string)$number;
    }
}


if (!function_exists('GetCreditSMS')) {

    function GetCreditSMS()
    {

        if (session()->has('sms_data')) {
            $nextCheckTime = session('sms_data.last_check') + 3600;

            if (!$nextCheckTime < now()->getTimestamp() + 3600) {
                return session('sms_data');
            }
        }

        $response = Http::withHeaders([
            "Authorization" => "AccessKey " . config('user.sms.api_key')
        ])->get('http://rest.ippanel.com/v1/credit')->json();


        //Log::info('check sms credit');


        if ($response['status'] == "OK") {
            $sms = [
                'sms_credit' => (int)round($response['data']['credit']),
                'last_check' => now()->getTimestamp()
            ];
        } else {
            $sms = [
                'error' => 'خطا در ارتباط با پنل پیامک',
                'last_check' => now()->getTimestamp(),
                'sms_credit' => 0
            ];
        }

        session(['sms_data' => $sms]);

        return session('sms_data');
    }
}
