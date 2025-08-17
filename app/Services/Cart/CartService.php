<?php

namespace App\Services\Cart;


use App\Services\Cart\Exceptions\QuantityExceededException;

use Gloudemans\Shoppingcart\Cart;
use Illuminate\Support\Collection;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;

class CartService
{
    const DEFAULT_INSTANCE = 'shopping';

    /**
     * @var string
     */
    private $instance;

    protected Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
        $this->instance(self::DEFAULT_INSTANCE);

    }

    /**
     * @param Product $product
     * @param int $quantity
     * @param array|null $option
     */
    public function add(Product $product, int $quantity, array $option = null)
    {

        // $this->hasStock($product, $quantity, $option);

        //$option = (object) $option['variant'];

        $option != null ? $options = [
            "variant_id" => $option['variant']->id,
            $option['variant']->variant->type =>
                $option['variant']->variant->type == "size"
                    ? $option['variant']->option->valuable->title
                    : $option['variant']->option->valuable->code
        ] : $options = [];

        $data = [
            "id" => $product->id,
            "name" => $product->name,
            "qty" => $quantity,
            "price" => $option != null
                ? $option['variant']->sales_price
                : $product->detail->sales_price,
            "options" => $options
        ];

        $this->cart
            ->instance($this->instance)
            ->add($data);

        $this->store();
    }

    /**
     * @throws QuantityExceededException
     */
    public function update($item, $qty)
    {
        if ($this->hasItem($item->id)) {
            $product = Product::findOrFail($item->id);

            $this->hasStock($product, $qty, $item->options);
            $this->cart
                ->instance($this->instance)
                ->update($item->rowId, $qty);
        }
        $this->store();

    }

    public function all()
    {
        $products = [];
        $items = $this->getContent()->values();

        foreach ($items as $item) {
            $product = Product::findOrFail($item->id);
            $product->quantity = $item->qty;
            if (!$item->options->isEmpty()) {
                $product->variantSelected = ProductVariant::findOrFail($item->options->variant_id);
            }

            $products[] = $product;
        }
        return $products;
    }

    public function has($default): bool
    {
        $content = $this->getContent();

        if (!is_null($default['variant_id'])) {
            return $content->contains('id', $default['product_id']) and $content->contains('options.variant_id', $default['variant_id']);
        }
        return $content->contains('id', $default['product_id']);
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->cart->instance($this->instance)->destroy();
    }

    public function remove($rowId)
    {
        $this->cart->instance($this->instance)->remove($rowId);
    }

    public function get($default)
    {
        $content = $this->getContent();
        //dd($content);
        return $content->where('id', $default['product_id'])->where('options.variant_id', $default['variant_id'])->first();
    }

    /**
     * calculate sub total after apply product discount
     * @return int
     */
    public function subTotal(): int
    {
        $total = 0;

        foreach ($this->all() as $item) {
            if ($item->variantSelected != null AND !$item->detail->isActivePromotion()) {
                $total += $item->variantSelected->sales_price * $item->quantity;
            } else {
                if ($item->detail->isActivePromotion()) {
                    $total += $item->detail->promotion_price * $item->quantity;
                } else {
                    $total += $item->detail->sales_price * $item->quantity;
                }
            }
        }
        return $total;
    }

    public function itemCount(): int
    {
        return $this->cart->instance($this->instance)->count();
    }

    private function hasStock(Product $product, int $quantity, $options = null)
    {

        if (!$options->isEmpty()) {

            $productVariant = ProductVariant::findOrFail($options['variant_id']);
            if (!$productVariant->hasStock($quantity)) {
                throw new QuantityExceededException('تعداد درخواستی این تنوع موجود نمی‌باشد!');
            }
        } else {
            if (!$product->hasStock($quantity)) {
                throw new QuantityExceededException('تعداد درخواستی موجود نمی‌باشد!');
            }
        }

        // return $quantity;
    }

    /**
     * @param string|null $instance
     * @return $this
     */
    public function instance(string $instance = null): CartService
    {
        $instance = $instance ?: self::DEFAULT_INSTANCE;

        $this->instance = $instance;


        return $this;
    }

    protected function getContent()
    {

        return session()->has('cart.' . $this->instance)
            ? session()->get('cart.' . $this->instance)
            : new Collection();
    }

    public function exists(): bool
    {
        return \Modules\Front\Entities\Cart::query()->where('identifier', auth('customer')->id())
            ->where('instance', $this->instance)
            ->exists();
    }

    public function store(): void
    {
       // dd('store');
        if (auth('customer')->check()) {
            $this->cart->instance($this->instance)->store(auth('customer')->id());
        }
    }

    public function restore(): void
    {
        dd('restore');

        if (auth('customer')->check()) {
            $this->cart->instance($this->instance)->restore(auth('customer')->id());
        }
    }

    public function deleteStoredCart(): void
    {
        if (auth('customer')->check()) {
            $this->cart->instance($this->instance)->deleteStoredCart(auth('customer')->id());
        }
    }

    private function hasItem($id)
    {
        $content = $this->getContent();
        return $content->contains('id', $id);
    }

    public function weight()
    {
        $weight = 0;
        foreach ($this->all() as $item) {
            if ($item->detail->weight == 0) {
                return 0;
            }
            $weight += $item->detail->weight * $item->quantity;
        }

        return $weight;
    }

    public function totalItem(): int
    {
        $products = $this->all();
        return count($products);
    }


    /**
     * calculate sub total before apply product discount
     * @return float|int
     */
    public function subtotalBeforeDiscount()
    {
        $total = 0;
        foreach ($this->all() as $item) {
            $total += ($item->variantSelected != null AND !$item->detail->isActivePromotion())
                ? $item->variantSelected->sales_price * $item->quantity
                : $item->detail->sales_price * $item->quantity;
        }
        return $total;
    }

    /**
     * calculate sub discount
     * @return float|int
     */
    public function subDiscount()
    {
        $total = 0;

        foreach ($this->all() as $item) {
            $total += $item->detail->isActivePromotion() ? ($item->detail->sales_price * $item->quantity) - ($item->detail->promotion_price * $item->quantity) : 0;
        }

        return $total;
    }


}
