<?php


namespace Modules\Coupon\Services\Discount\Coupon\Validator\Contracts;



use Modules\Coupon\Entities\Coupon;

abstract class AbstractCouponValidator implements CouponValidatorInterface
{
    private $nextValidator;


    public function setNextValidator(CouponValidatorInterface $validator)
    {
        $this->nextValidator = $validator;
    }

    public function validate(Coupon $coupon)
    {
        if ($this->nextValidator === null) return true;
        return $this->nextValidator->validate($coupon);

    }
}
