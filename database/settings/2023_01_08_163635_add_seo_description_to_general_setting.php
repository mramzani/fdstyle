<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddSeoDescriptionToGeneralSetting extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.seo_description','توضیحات متا سایت');
    }
}
