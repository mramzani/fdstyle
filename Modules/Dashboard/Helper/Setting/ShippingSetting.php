<?php

namespace Modules\Dashboard\Helper\Setting;

use Spatie\LaravelSettings\Settings;

class ShippingSetting extends Settings
{

    public int $shipping_cost;
    public int $shipping_free_cost;

    public static function group(): string
    {
        return 'shipping';
    }
}
