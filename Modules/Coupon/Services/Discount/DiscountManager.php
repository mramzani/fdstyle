<?php


namespace Modules\Coupon\Services\Discount;



use App\Services\Cost\CartCost;
use Modules\Coupon\Entities\Coupon;

class DiscountManager
{
    private $cartCost;

    private $discountCalculator;

    public function __construct(CartCost $cartCost,DiscountCalculator $discountCalculator)
    {
        $this->cartCost = $cartCost;
        $this->discountCalculator = $discountCalculator;
    }

    public function calculateUserDiscount(): int
    {
        if (!session()->has('coupon')) return 0;

        $coupon = Coupon::find(session()->get('coupon'))->first();

        if (!is_null($coupon) AND !$coupon->isExpired() AND $coupon->isEnable()){
            return $this->discountCalculator->discountAmount($coupon,$this->cartCost->getTotalCosts());
        }
        return 0;
    }
}
