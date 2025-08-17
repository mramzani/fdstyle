<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateProductSetting extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('product.carousel_pagination_number',10);
        $this->migrator->add('product.product_prefix','');
        $this->migrator->add('product.tax_is_active',false);
        $this->migrator->add('product.display_product_without_image',false);
        $this->migrator->add('product.preparation_time',24);
    }
}
