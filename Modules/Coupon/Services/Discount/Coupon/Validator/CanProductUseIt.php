<?php

namespace Modules\Coupon\Services\Discount\Coupon\Validator;

use App\Services\Cart\CartService;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\CanNotUseThisProductException;
use Modules\Coupon\Services\Discount\Coupon\Validator\Contracts\AbstractCouponValidator;

class CanProductUseIt extends AbstractCouponValidator
{
    private CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function validate(Coupon $coupon)
    {
        if (!$this->cart->all()->contains($coupon->couponable)) {
            throw new CanNotUseThisProductException('این کد تخفیف قابل استفاده برای محصول انتخابی شما نمی‌باشد.');
        }
        return parent::validate($coupon);
    }
}
