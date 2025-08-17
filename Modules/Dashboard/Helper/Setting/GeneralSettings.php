<?php

namespace Modules\Dashboard\Helper\Setting;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $short_name;
    public string $telegram_link = "";
    public string $instagram_link = "";
    public string $twitter_link = "";
    public string $facebook_link = "";
    public string $copy_right_text = "";
    public int $otp_expiration_period = 300;
    public string $general_customer_mobile;
    public string $admin_mobile;
    public string $seo_description;

    public static function group(): string
    {
        return 'general';
    }
}
