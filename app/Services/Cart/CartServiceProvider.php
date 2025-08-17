<?php

namespace App\Services\Cart;




use Gloudemans\Shoppingcart\Cart;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cart',function (){
            return new CartService(resolve(Cart::class));
        });
    }
}
