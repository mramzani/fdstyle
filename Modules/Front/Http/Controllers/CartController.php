<?php

namespace Modules\Front\Http\Controllers;


use App\Services\Cart\Exceptions\QuantityExceededException;


use App\Services\Cart\Facades\Cart;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;

class CartController extends BaseController
{
    public function add(Product $product,ProductVariant $variant = null)
    {
        try {
            $options = null;
            if (! is_null($variant)){
                $options = [
                    "variant" => $variant,
                ];
            }
            Cart::instance('shopping')->add($product,1,$options);
            return back()->with('alertSuccess','محصول با موفقیت به سبد خرید افزوده شد!');
        } catch (QuantityExceededException $exception){
            return back()->with('alertWarning','این محصول به تعداد درخواستی موجود نمی‌باشد');
        }

    }

    public function all()
    {
        return view('front::cart.list');
    }

    public function clear()
    {
        Cart::instance('shopping')->clear();
        Cart::instance('shopping')->deleteStoredCart();
        return redirect('/cart');
    }

    public function shipping()
    {
        session()->forget('default_address');

        if (Cart::instance('shopping')->itemCount() == 0){
            return redirect('/cart');
        }

        return view('front::shipping.index');
    }
}
