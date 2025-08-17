<?php

namespace App\Services\Cost;

use App\Services\Cart\Facades\Cart;
use App\Services\Cost\Contract\CostInterface;

class ProductsCost implements CostInterface
{

    private $cart;

    public function __construct()
    {
        $this->cart = Cart::instance('shopping');
    }

    public function getCost(): int
    {
        return $this->cart->subtotalBeforeDiscount();
    }

    public function getTotalCosts(): int
    {
        return $this->getCost();
    }

    public function persianDescription(): string
    {
        return 'قیمت کالاها';
    }

    public function getSummery(): array
    {
        return [$this->persianDescription() => ['amount' => $this->getCost(),'class' => 'text-muted'] ];
    }
}
