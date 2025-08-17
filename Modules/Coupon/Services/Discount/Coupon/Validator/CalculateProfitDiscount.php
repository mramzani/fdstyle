<?php

namespace Modules\Coupon\Services\Discount\Coupon\Validator;



use App\Services\Cart\CartService;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\DiscountCanNotMoreThanProfit;
use Modules\Coupon\Services\Discount\DiscountCalculator;


class CalculateProfitDiscount extends Contracts\AbstractCouponValidator
{
    private CartService $cart;
    private $discountCalculator;
    public function __construct(CartService $cart,DiscountCalculator $discountCalculator)
    {
        $this->cart = $cart;
        $this->discountCalculator = $discountCalculator;
    }

    public function validate(Coupon $coupon)
    {
        $profit = 0;
        $orderAmount = 0;
        foreach($this->cart->all() as $product){
            $product_price = $product->detail->isActivePromotion() ? $product->detail->promotion_price : $product->detail->sales_price;
            $profit += (int) ($product_price - $product->detail->purchase_price) * $product->quantity;
            $orderAmount +=  $product_price * $product->quantity;
        }


        $discountAmount = $this->discountCalculator->discountAmount($coupon,$orderAmount);

        if ($discountAmount >= $profit/2){
            throw new DiscountCanNotMoreThanProfit('مبلغ سفارش برای این کد تخفیف معتبر نمی‌باشد! مبلغ سفارش خود را افزایش دهید');
        }
        return parent::validate($coupon);

    }
}
