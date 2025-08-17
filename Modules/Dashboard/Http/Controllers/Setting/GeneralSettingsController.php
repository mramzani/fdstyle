<?php

namespace Modules\Dashboard\Http\Controllers\Setting;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Helper\Setting\GeneralSettings;

class GeneralSettingsController extends Controller
{
    public function show(GeneralSettings $generalSettings)
    {

        return view('dashboard::settings.general',[
            'short_name' => $generalSettings->short_name,
            'seo_description' => $generalSettings->seo_description,
            'telegram_link' => $generalSettings->telegram_link,
            'instagram_link' => $generalSettings->instagram_link,
            'twitter_link' => $generalSettings->twitter_link,
            'facebook_link' => $generalSettings->facebook_link,
            'copy_right_text' => $generalSettings->copy_right_text,
            'otp_expiration_period' => $generalSettings->otp_expiration_period,
            'general_customer_mobile' => $generalSettings->general_customer_mobile,
            'admin_mobile' => $generalSettings->admin_mobile,
        ]);

    }

    public function update(GeneralSettings $generalSettings,Request $request)
    {
        ///validation
        $request->validate([
            'short_name' => 'required',
            'telegram_link' => 'string|required',
            'instagram_link' => 'string|required',
            'twitter_link' => 'string|required',
            'facebook_link' => 'string|required',
            'copy_right_text' => 'string|required',
            'otp_expiration_period' => 'int|required',
            'general_customer_mobile' => 'string|nullable',
            'admin_mobile' => 'string|nullable',
            'seo_description' => 'string|min:70|max:200',
        ]);

        $generalSettings->short_name = $request->input('short_name');
        $generalSettings->seo_description = $request->input('seo_description');
        $generalSettings->telegram_link = $request->input('telegram_link');
        $generalSettings->instagram_link = $request->input('instagram_link');
        $generalSettings->twitter_link = $request->input('twitter_link');
        $generalSettings->facebook_link = $request->input('facebook_link');
        $generalSettings->copy_right_text = $request->input('copy_right_text');
        $generalSettings->otp_expiration_period = $request->input('otp_expiration_period');
        $generalSettings->general_customer_mobile = $request->input('general_customer_mobile') == null ? "" : $request->input('general_customer_mobile');
        $generalSettings->admin_mobile = $request->input('admin_mobile') == null ? "" : $request->input('admin_mobile');
        $generalSettings->save();

        return back()->with('alertSuccess','تنظیمات باموفقیت بروزرسانی شد.');
    }
}
