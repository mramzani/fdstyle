<?php

namespace Modules\Dashboard\Helper\Setting;

use Spatie\LaravelSettings\Settings;

class ThirdPartySettings extends Settings
{
    public string $e_symbol_code;

    public string $samandehi_code;

    public string $mediaad;

    public string $goftino;

    public string $google_tracking_id;

    public string $ippanel_api_key;
    public string $ippanel_pattern_id;

    public string $zibal_api_key;
    public string $zibal_merchant_id;
    public string $zibal_wallet_id;

    public string $telegram_chat_id;
    public string $telegram_bot_token;


    public static function group(): string
    {
        return 'third_party';
    }
}
