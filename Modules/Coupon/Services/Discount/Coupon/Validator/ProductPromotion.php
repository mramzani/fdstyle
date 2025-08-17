<?php

namespace Modules\Coupon\Services\Discount\Coupon\Validator;

use App\Services\Cart\CartService;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\ProductIsPromotionException;
use Modules\Coupon\Services\Discount\Coupon\Validator\Contracts\AbstractCouponValidator;

class ProductPromotion extends AbstractCouponValidator
{

    private CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @throws ProductIsPromotionException
     */
    public function validate(Coupon $coupon)
    {
        foreach ($this->cart->all() as $product){
            if ($product->detail->isActivePromotion()){
                throw new ProductIsPromotionException('برای محصولات دارای تخفیف امکان استفاده از کدتخفیف وجود ندارد. ');
            }
        }
        return parent::validate($coupon);
    }
}
