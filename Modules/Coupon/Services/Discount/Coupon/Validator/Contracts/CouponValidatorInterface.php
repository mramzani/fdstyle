<?php


namespace Modules\Coupon\Services\Discount\Coupon\Validator\Contracts;




use Modules\Coupon\Entities\Coupon;

interface CouponValidatorInterface
{
    public function setNextValidator(CouponValidatorInterface $validator);

    public function validate(Coupon $coupon);
  }
