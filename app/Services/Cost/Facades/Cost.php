<?php

namespace App\Services\Cost\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * * Cart Class
 * @package Cart
 * @method static getCost()
 * @method static getTotalCosts()
 * @method static persianDescription()
 * @method static getSummery()
 */
class Cost extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cost';
    }
}
