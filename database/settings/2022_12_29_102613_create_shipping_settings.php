<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class createShippingSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('shipping.shipping_cost',44000);
        $this->migrator->add('shipping.shipping_free_cost',0);
    }
}
