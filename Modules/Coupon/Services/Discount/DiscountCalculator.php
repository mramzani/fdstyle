<?php


namespace Modules\Coupon\Services\Discount;



use Modules\Coupon\Entities\Coupon;

class DiscountCalculator
{
    public function discountAmount(Coupon $coupon,int $amount):int
    {
        $discountAmount = (int) (($coupon->percent / 100) * $amount);
        return $this->isExceeded($discountAmount,$coupon->limit) ? $coupon->limit : $discountAmount;

    }

    private function isExceeded(int $amount,int $limit)
    {
        return $amount > $limit;
    }
}
