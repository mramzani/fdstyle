<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class AddGeneralCustomerMobile extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.general_customer_mobile','');
        $this->migrator->add('general.admin_mobile','');
    }
}
