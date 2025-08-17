<?php

namespace Modules\Coupon\Services\Discount\Coupon\Validator;

use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\CouponHasDisableOrUsed;
use Modules\Coupon\Services\Discount\Coupon\Validator\Contracts\AbstractCouponValidator;


class IsEnable extends AbstractCouponValidator
{
    public function validate(Coupon $coupon)
    {
        if (!$coupon->isEnable()){
            throw new CouponHasDisableOrUsed('کد تخفیف وارد شده معتبر نمی‌باشد.');
        }
        return parent::validate($coupon);
    }
}
