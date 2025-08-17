<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.short_name', '');
        $this->migrator->add('general.telegram_link', '#');
        $this->migrator->add('general.instagram_link', '#');
        $this->migrator->add('general.twitter_link', '#');
        $this->migrator->add('general.facebook_link', '#');
        $this->migrator->add('general.copy_right_text', '');
        $this->migrator->add('general.otp_expiration_period', 300);
    }

}
