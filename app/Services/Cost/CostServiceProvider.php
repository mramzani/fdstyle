<?php

namespace App\Services\Cost;

use App\Services\Cart\Facades\Cart;
use App\Services\Cost\Contract\CostInterface;
use App\Services\Shipping\ShippingManager;
use Illuminate\Support\ServiceProvider;
use Modules\Coupon\Services\Discount\DiscountManager;

class CostServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('cost', function ($app) {
            $showShippingCostInPath = [
                'shipping',
                'order/payment'
            ];

            $productsCost = new ProductsCost();
            $cartCost = new CartCost($productsCost);

            if ((in_array(request()->has('fingerprint') and request()->get('fingerprint')['path'], $showShippingCostInPath))
                or in_array(request()->path(), $showShippingCostInPath)) {

                $discountCost = new DiscountCost($cartCost);
                $couponCost = new CouponCost($discountCost,$app->make(DiscountManager::class));

                return new ShippingCost($couponCost, $app->make(ShippingManager::class));

            }
            return new DiscountCost($cartCost);
        });

    }
}
