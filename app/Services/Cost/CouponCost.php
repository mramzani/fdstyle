<?php

namespace App\Services\Cost;

use App\Services\Cost\Contract\CostInterface;
use Modules\Coupon\Services\Discount\DiscountManager;

class CouponCost implements CostInterface
{

    private DiscountManager $discountManager;
    private DiscountCost $cost;

    public function __construct(DiscountCost $cost,DiscountManager $discountManager)
    {
        $this->discountManager = $discountManager;
        $this->cost = $cost;
    }

    public function getCost(): int
    {
        return $this->discountManager->calculateUserDiscount();
    }

    public function getTotalCosts(): int
    {
        return $this->cost->getTotalCosts() - $this->getCost();
    }

    public function persianDescription(): string
    {
        return 'کد تخفیف';
    }

    public function getSummery(): array
    {
        return array_merge($this->cost->getSummery(),[$this->persianDescription() => ['amount' => $this->getCost(),'class' => 'text-danger']]);
    }
}
