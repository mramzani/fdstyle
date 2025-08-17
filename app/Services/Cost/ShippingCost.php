<?php

namespace App\Services\Cost;

use App\Services\Cost\Contract\CostInterface;
use App\Services\Shipping\ShippingManager;
use Illuminate\Http\Request;
use Modules\Dashboard\Helper\Setting\ShippingSetting;

class ShippingCost implements CostInterface
{

    /**
     * @var ShippingManager
     */
    protected ShippingManager $shippingCost;

    protected CouponCost $cost;


    public function __construct(CouponCost $cost,ShippingManager $shippingCost)
    {
        $this->shippingCost = $shippingCost;
        $this->cost = $cost;
    }

    public function getCost(): int
    {

        $shipping_free_cost = resolve(ShippingSetting::class)->shipping_free_cost;

        if ($shipping_free_cost != 0 and $shipping_free_cost <= $this->cost->getTotalCosts()){
            return 0;
        } else {
            return $this->shippingCost->calculateShippingPrice();
        }
    }

    public function getTotalCosts(): int
    {
        return $this->cost->getTotalCosts() + $this->getCost();
    }

    public function persianDescription(): string
    {
        return 'هزینه ارسال';
    }

    public function getSummery(): array
    {
        return array_merge($this->cost->getSummery(),[$this->persianDescription() => ['amount' => $this->getCost() , 'class' => 'text-muted']]);
    }
}
