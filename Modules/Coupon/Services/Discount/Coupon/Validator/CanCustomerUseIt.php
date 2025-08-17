<?php

namespace Modules\Coupon\Services\Discount\Coupon\Validator;

use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\CustomerCanNotUseItException;

class CanCustomerUseIt extends Contracts\AbstractCouponValidator
{
    public function validate(Coupon $coupon)
    {
        if (!auth()->guard('customer')->user()->coupons->contains($coupon)){
            throw new CustomerCanNotUseItException('این کد تخفیف متعلق به شما نیست.');
        }
        return parent::validate($coupon);
    }
}
