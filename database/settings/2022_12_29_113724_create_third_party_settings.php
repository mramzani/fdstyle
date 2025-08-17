<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class createThirdPartySettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('third_party.e_symbol_code','');
        $this->migrator->add('third_party.samandehi_code','');
        $this->migrator->add('third_party.mediaad','');
        $this->migrator->add('third_party.goftino','');
        $this->migrator->add('third_party.google_tracking_id','');
        $this->migrator->add('third_party.ippanel_api_key','');
        $this->migrator->add('third_party.ippanel_pattern_id','');
        $this->migrator->add('third_party.zibal_api_key','');
        $this->migrator->add('third_party.zibal_merchant_id','');
        $this->migrator->add('third_party.zibal_wallet_id','');
        $this->migrator->add('third_party.telegram_chat_id','');
        $this->migrator->add('third_party.telegram_bot_token','');
    }
}
