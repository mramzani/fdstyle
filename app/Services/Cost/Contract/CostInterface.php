<?php

namespace App\Services\Cost\Contract;

interface CostInterface
{
    public function getCost(): int;
    public function getTotalCosts(): int;
    public function persianDescription(): string;
    public function getSummery(): array;
}
