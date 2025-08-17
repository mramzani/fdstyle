<?php

namespace Modules\Front\Http\Livewire\Cart;

use App\Services\Cart\Exceptions\QuantityExceededException;
use App\Services\Cart\Facades\Cart;
use Livewire\Component;
use Modules\Front\Adapters\ProductAdapter;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;
use function Symfony\Component\VarDumper\Dumper\esc;

class BtnAddCart extends Component
{
    public $product;
    protected ?Product $item = null;
    public $qty = 1;
    public $variant;
    public $variantSelected;
    public $price = 0;
    public $variantTitle = '';

    public function mount($product)
    {
        $this->item = $product;

        if (!is_null($this->item)) {

            $productResource = new ProductAdapter($this->item);
            $this->product = $productResource->transform();


            $object = (object)$this->product;

            if (!empty($object->default_variant)) {

                $this->price = $object->default_variant['selling_price'];

                if (array_key_exists('color', $object->default_variant) || array_key_exists('size', $object->default_variant)) {
                    $this->variantTitle = array_key_exists('color', $object->default_variant)
                        ? $object->default_variant['color']['title']
                        : $object->default_variant['size']['title'];
                }


                $this->variant = [
                    'variant_id' => $object->default_variant['variant_id'],
                    'product_id' => $object->default_variant['product_id'],
                ];

                //$this->variantSelected = $object->default_variant['variant_id'];
                $this->variantSelected = null;
                if (Cart::instance('shopping')->has($object->default_variant)) {
                    $this->qty = Cart::instance('shopping')->get($this->variant)->qty;
                }
            }
        }

    }

    public function render()
    {
        return view('front::livewire.cart.btn-add-cart');
    }

    public function addCart()
    {
        $options = null;
        if (!is_null($this->variant['variant_id'])) {
            $options = ["variant" => $this->getProductVariant($this->variant['variant_id'])];
        }
        $product = $this->getProduct($this->variant['product_id']);


        Cart::instance('shopping')
            ->add($product, $this->qty, $options);

        $this->emit('cart.updated');

        $this->showAlert('success', 'محصول به سبد خرید افزوده شد');
    }

    public function changeVariant($variantId, $title)
    {

        $this->price = $this->getVariant($variantId)['price'];

        $this->variantTitle = $title;

        $this->variantSelected = $variantId;

        $this->variant['variant_id'] = $this->variantSelected;

        $item = $this->getItem();

        $item != null
            ? $this->qty = $item->qty
            : $this->qty = 1;

    }

    public function up()
    {
        $item = $this->getItem();
        $this->qty += 1;
        $this->updateCart($item, $this->qty);

    }

    public function down()
    {
        $item = $this->getItem();

        if ($this->qty == 1 && Cart::instance('shopping')->has($this->variant)) {
            //remove
            Cart::instance('shopping')->remove($item->rowId);
            // emit
            $this->emit('cart.updated');
            // message
            $this->showAlert('success', 'سبد خرید با موفقیت بروزرسانی شد');
        } else {
            $this->qty != 1 ? $this->qty -= 1 : $this->qty = 1;
            $this->updateCart($item, $this->qty);
        }
    }

    private function getItem()
    {
        if (Cart::instance('shopping')->has($this->variant)) {
            return Cart::instance('shopping')->get($this->variant);
        }
        return null;
    }

    private function updateCart($item, $qty)
    {
        try {
            Cart::instance('shopping')->update($item, $qty);
            $this->emit('cart.updated');
            $this->showAlert('success', 'سبد خرید با موفقیت بروزرسانی شد');
        } catch (QuantityExceededException $exception) {
            $this->qty = $item->qty;
            $this->showAlert('warning', 'محصول به تعداد درخواستی موجود نمی‌باشد');
        }

    }

    private function getProduct($id)
    {
        return Product::findOrFail($id);
    }

    private function getProductVariant($variant_id)
    {
        return ProductVariant::findOrFail($variant_id);
    }

    private function getVariant($variant_id)
    {
        return collect($this->product['variants']['list'])->where('id',$variant_id)->first();
    }

    private function showAlert($type, $message)
    {
        $this->dispatchBrowserEvent('show-toast', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}
