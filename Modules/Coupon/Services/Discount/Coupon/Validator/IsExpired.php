<?php


namespace Modules\Coupon\Services\Discount\Coupon\Validator;




use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\CouponHasExpiredException;
use Modules\Coupon\Services\Discount\Coupon\Validator\Contracts\AbstractCouponValidator;

class IsExpired extends AbstractCouponValidator
{
    /**
     * @throws CouponHasExpiredException
     */
    public function validate(Coupon $coupon)
    {
        if ($coupon->isExpired()){
            throw new CouponHasExpiredException('مهلت استفاده از این کد تخفیف به پایان رسید.');
        }
        return parent::validate($coupon);
    }

}
