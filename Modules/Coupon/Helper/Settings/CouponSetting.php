<?php

namespace Modules\Coupon\Helper\Settings;

use Spatie\LaravelSettings\Settings;

class CouponSetting extends Settings
{

    public bool $status;
    public string $coupon_prefix;
    public int $coupon_percent;
    public int $coupon_limit;
    public int $min_basket_amount;
    public int $allowed_qty;
    public int $deadline;

    public static function group(): string
    {
        return 'coupon';
    }
}
