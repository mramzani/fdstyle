<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('coupon.status',false);
        $this->migrator->add('coupon.coupon_prefix','');
        $this->migrator->add('coupon.coupon_percent',0);
        $this->migrator->add('coupon.coupon_limit',0);
        $this->migrator->add('coupon.min_basket_amount',0);
        $this->migrator->add('coupon.allowed_qty',0);
        $this->migrator->add('coupon.deadline',0);
    }
};
