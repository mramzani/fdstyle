<?php

namespace App\Services\Cost;

use App\Services\Cart\Facades\Cart;
use App\Services\Cost\Contract\CostInterface;

class DiscountCost implements CostInterface
{
    /**
     * @var $cart
     */
    private $cart;
    private CartCost $cost; //CartCost $cost

    public function __construct(CartCost $cost)//CartCost $cost
    {
        $this->cart = Cart::instance('shopping');
        $this->cost = $cost;
    }

    public function getCost(): int
    {
        return $this->cart->subDiscount();
    }

    public function getTotalCosts(): int
    {
        return $this->cost->getTotalCosts();
    }

    public function persianDescription(): string
    {
        return 'سود شما از خرید';
    }

    public function getSummery(): array
    {
        return array_merge($this->cost->getSummery(),[$this->persianDescription() => ['amount' => $this->getCost(),'class' => 'text-danger']]);
    }
}
