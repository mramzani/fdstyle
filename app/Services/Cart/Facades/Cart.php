<?php

namespace App\Services\Cart\Facades;

use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Facade;
use Modules\Product\Entities\Product;

/**
 * Cart Class
 * @package Cart
 * @method static add(Product $product,int $quantity,array $option = [])
 * @method static all()
 * @method static has($product)
 * @method static get($variant)
 * @method static clear()
 * @method static deleteStoredCart()
 * @method static CartService instance($instance = null)
 * @method static remove($rowId)
 * @method static unset(Product $product)
 * @method static int itemCount()
 * @method static int totalItem()
 * @method static store()
 * @method static restore()
 * @method static weight()
 * @method static int subTotal()
 */
class Cart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cart';
    }
}
