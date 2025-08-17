<?php


namespace Modules\Coupon\Services\Discount\Coupon\Validator;





use App\Services\Cart\CartService;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Services\Discount\Coupon\Exceptions\CanNotUseThisCategoryException;


class CanCategoryUseIt extends Contracts\AbstractCouponValidator
{
    private CartService $cart;
    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function validate(Coupon $coupon)
    {
        foreach ($this->cart->all() as $product){
            if (!$product->categories->contains($coupon->couponable)){
                throw new CanNotUseThisCategoryException('این کد تخفیف قابل استفاده برای دسته بندی محصولات انتخابی شما نمی‌باشد.');
            }
        }

        return parent::validate($coupon);
    }

}
