<?php

namespace Modules\Dashboard\Helper\Setting;

use Spatie\LaravelSettings\Settings;

class ProductSettings extends Settings
{
    public int $carousel_pagination_number;
    public string $product_prefix;
    public bool $tax_is_active;
    public bool $display_product_without_image = false;
    public int $preparation_time;
    public int $default_guarantee_id;

    public static function group(): string
    {
        return 'product';
    }
}
