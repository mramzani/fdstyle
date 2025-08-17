<?php

namespace Modules\Coupon\Services\Discount\Coupon\Validator;

use App\Services\Cart\CartService;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\minimumCartAmountExeption;
use Modules\Coupon\Services\Discount\Coupon\Validator\Contracts\AbstractCouponValidator;

class MinCartAmount extends AbstractCouponValidator
{
    private CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function validate(Coupon $coupon)
    {
        if ($coupon->min_basket_amount > $this->cart->subTotal()){
            throw new minimumCartAmountExeption( 'حداقل مبلغ سبد خرید برای استفاده از این کد تخفیف ' . number_format($coupon->min_basket_amount) . ' تومان می‌باشد');
        }
        return parent::validate($coupon);
    }
}
