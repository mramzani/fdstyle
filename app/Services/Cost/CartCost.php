<?php

namespace App\Services\Cost;

use App\Services\Cart\Facades\Cart;
use App\Services\Cost\Contract\CostInterface;

class CartCost implements CostInterface
{


    private $cost;
    private \App\Services\Cart\CartService $cart;

    public function __construct(ProductsCost $cost)
    {
        $this->cart = Cart::instance('shopping');
        $this->cost = $cost;
    }

    public function getCost(): int
    {
        return $this->cart->subTotal();
    }

    public function getTotalCosts(): int
    {
        return $this->getCost();
    }

    public function persianDescription(): string
    {
        return 'جمع سبد خرید';
    }

    public function getSummery(): array
    {
        return array_merge($this->cost->getSummery(),[$this->persianDescription() => ['amount' => $this->getCost(),'class' => 'text-black']]);
    }
}
