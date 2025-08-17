<?php


use Modules\Coupon\Helper\Settings\CouponSetting;
use Modules\Dashboard\Helper\Setting\GeneralSettings;
use Modules\Dashboard\Helper\Setting\ProductSettings;
use Modules\Dashboard\Helper\Setting\ThirdPartySettings;
use Modules\PageBuilder\Entities\Page;


if (!function_exists('get_zibal_merchant_id')) {
    function get_zibal_merchant_id()
    {
        if (config('app.env') !== 'build') {
            return app(ThirdPartySettings::class)->zibal_merchant_id;
        }
        return '';
    }

}

if (!function_exists('get_zibal_api_key')) {
    function get_zibal_api_key()
    {
        if (config('app.env') !== 'build') {
            return app(ThirdPartySettings::class)->zibal_api_key;
        }
        return '';
    }
}

if (!function_exists('get_zibal_wallet_id')) {
    function get_zibal_wallet_id()
    {
        if (config('app.env') !== 'build') {
            return app(ThirdPartySettings::class)->zibal_wallet_id;
        }
        return '';
    }
}

if (!function_exists('get_ippanel_api_key')) {
    function get_ippanel_api_key(): string
    {
        if (config('app.env') !== 'build') {
            return app(ThirdPartySettings::class)->ippanel_api_key;
        }
        return '';
    }
}

if (!function_exists('get_ippanel_pattern_id')) {
    function get_ippanel_pattern_id()
    {
        if (config('app.env') !== 'build') {
            return app(ThirdPartySettings::class)->ippanel_pattern_id;
        }
        return '';
    }
}

if (!function_exists('get_product_prefix')) {
    function get_product_prefix()
    {
        if (config('app.env') !== 'build') {
            return app(ProductSettings::class)->product_prefix;
        }
        return '';
    }
}

if (!function_exists('get_default_guarantee')) {
    function get_default_guarantee()
    {
        if (config('app.env') !== 'build') {
            $guarantee_id = app(ProductSettings::class)->default_guarantee_id;
            $guarantee = \Modules\Dashboard\Entities\Guarantee::select(['title','description','link'])
                ->where('id',$guarantee_id)
                ->first();
            return $guarantee ?? null;
        }
        return null;
    }
}

if (!function_exists('get_short_name')) {
    function get_short_name()
    {
        if (config('app.env') !== 'build') {
            return app(GeneralSettings::class)->short_name;
        }
        return '';
    }
}

if (!function_exists('get_seo_description')) {
    function get_seo_description()
    {
        if (config('app.env') !== 'build') {
            return app(GeneralSettings::class)->seo_description;
        }
        return '';
    }
}

//purchase guide route
if (!function_exists('purchase_guide')) {
    function purchase_guide(): string
    {
        if (Page::where('slug','purchase-guide')->exists()) {
            return route('front.page.show','purchase-guide');
        }
        return '#';
    }
}

if (!function_exists('shipping_methods')) {
    function shipping_methods(): string
    {
        if (Page::where('slug','shipping-methods')->exists()) {
            return route('front.page.show','shipping-methods');
        }
        return '#';
    }
}

if (!function_exists('payment_methods')) {
    function payment_methods(): string
    {
        if (Page::where('slug','payment-methods')->exists()) {
            return route('front.page.show','payment-methods');
        }
        return '#';
    }
}

//customer services route
if (!function_exists('faq')) {
    function faq(): string
    {
        if (Page::where('slug','faq')->exists()) {
            return route('front.page.show','faq');
        }
        return '#';
    }
}

if (!function_exists('return_policy')) {
    function return_policy(): string
    {
        if (Page::where('slug','return-policy')->exists()) {
            return route('front.page.show','return-policy');
        }
        return '#';
    }
}

if (!function_exists('terms_conditions')) {
    function terms_conditions(): string
    {
        if (Page::where('slug','terms-conditions')->exists()) {
            return route('front.page.show','terms-conditions');
        }
        return '#';
    }
}

if (!function_exists('privacy_policy')) {
    function privacy_policy(): string
    {
        if (Page::where('slug','privacy-policy')->exists()) {
            return route('front.page.show','privacy-policy');
        }
        return '#';
    }
}

if (!function_exists('order_tracking')) {
    function order_tracking(): string
    {
        if (Page::where('slug','order-tracking')->exists()) {
            return route('front.page.show','order-tracking');
        }
        return '#';
    }
}

//about and contact US route
if (!function_exists('contact_us')) {
    function contact_us(): string
    {
        if (Page::where('slug','contact-us')->exists()) {
            return route('front.page.show','contact-us');
        }
        return '#';
    }
}

if (!function_exists('about_us')) {
    function about_us(): string
    {
        if (Page::where('slug','about-us')->exists()) {
            return route('front.page.show','about-us');
        }
        return '#';
    }
}

if (!function_exists('coupon_setting')) {
    function coupon_setting()
    {
        if (config('app.env') !== 'build') {
            return app(CouponSetting::class);
        }
        return null;
    }
}
