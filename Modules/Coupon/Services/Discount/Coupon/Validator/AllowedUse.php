<?php

namespace Modules\Coupon\Services\Discount\Coupon\Validator;




use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\CanNotAllowedUseException;
use Modules\Coupon\Services\Discount\Coupon\Validator\Contracts\AbstractCouponValidator;

class AllowedUse extends AbstractCouponValidator
{
    public function validate(Coupon $coupon)
    {
        if ($coupon->allowed_qty <= $coupon->used_qty){
            throw new CanNotAllowedUseException('تعداد دفعات مجاز استفاده از این کد تخفیف به پایان رسیده است.');
        }
        return parent::validate($coupon);
    }
}
