<?php


namespace Modules\Coupon\Services\Discount\Coupon;



use Modules\Coupon\Services\Discount\Coupon\Validator\AllowedUse;
use Modules\Coupon\Services\Discount\Coupon\Validator\CalculateProfitDiscount;
use Modules\Coupon\Services\Discount\Coupon\Validator\CanCategoryUseIt;
use Modules\Coupon\Services\Discount\Coupon\Validator\CanCustomerUseIt;
use Modules\Coupon\Services\Discount\Coupon\Validator\CanProductUseIt;
use Modules\Coupon\Services\Discount\Coupon\Validator\IsEnable;
use Modules\Coupon\Services\Discount\Coupon\Validator\IsExpired;
use Modules\Coupon\Services\Discount\Coupon\Validator\MinCartAmount;
use Modules\Coupon\Services\Discount\Coupon\Validator\ProductPromotion;
use Modules\Coupon\Entities\Coupon;

class CouponValidator
{
    public function isValid(Coupon $coupon)
    {
        $isExpired = resolve(IsExpired::class);
        $isEnable = resolve(IsEnable::class);
        $minCartAmount = resolve(MinCartAmount::class);
        $allowedUse = resolve(AllowedUse::class);
        $productPromotion = resolve(ProductPromotion::class);

        $canCustomerUseIt = resolve(CanCustomerUseIt::class);
        $canCategoryUseIt = resolve(CanCategoryUseIt::class);
        $canProductUseIt = resolve(CanProductUseIt::class);
        $DiscountIsNotMoreThanProfit = resolve(CalculateProfitDiscount::class);

        $isExpired->setNextValidator($isEnable);
        $isEnable->setNextValidator($minCartAmount);
        $minCartAmount->setNextValidator($allowedUse);
        $allowedUse->setNextValidator($productPromotion);
        $productPromotion->setNextValidator($DiscountIsNotMoreThanProfit);


        if($coupon->couponable_type == "App\Customer"){
            //$productPromotion->setNextValidator($canCustomerUseIt);
            $DiscountIsNotMoreThanProfit->setNextValidator($canCustomerUseIt);
        }
        if($coupon->couponable_type == "App\Category"){
            $productPromotion->setNextValidator($canCategoryUseIt);
            $DiscountIsNotMoreThanProfit->setNextValidator($canCustomerUseIt);
        }
        if($coupon->couponable_type == "App\Product"){
            $productPromotion->setNextValidator($canProductUseIt);
            $DiscountIsNotMoreThanProfit->setNextValidator($canCustomerUseIt);
        }


        return $isExpired->validate($coupon);
    }
}
