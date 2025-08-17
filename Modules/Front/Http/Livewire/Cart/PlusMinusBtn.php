<?php

namespace Modules\Front\Http\Livewire\Cart;

use App\Services\Cart\Exceptions\QuantityExceededException;
use App\Services\Cart\Facades\Cart;
use Livewire\Component;

class PlusMinusBtn extends Component
{
    public $product;
    public $variant;
    public $qty;

    public function mount($product)
    {
        $this->variant = [
            'product_id' => $product->id,
            'variant_id' => $product->variantSelected != null ? $product->variantSelected->id : null,
        ];

        $item = $this->getItem();
        $this->qty = $item->qty;

    }

    public function render()
    {
        return view('front::livewire.cart.plus-minus-btn');
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


        if ($this->qty == 1) {
            Cart::instance('shopping')->remove($item->rowId);
            $this->emit('cart.updated');
            $this->showAlert('success', 'محصول از سبد خرید حذف شد');
        } else {
            $this->qty > 1 ? $this->qty -= 1 : $this->qty = 1;
            $this->updateCart($item, $this->qty);
        }


    }

    /**
     * @throws QuantityExceededException
     */
    public function remove()
    {
        $item = $this->getItem();
        
        if (Cart::instance('shopping')->has($this->variant)){
            Cart::instance('shopping')->remove($item->rowId);
            $this->emit('cart.updated');
            $this->showAlert('success', 'محصول از سبد خرید حذف شد');
        } else {
            $this->showAlert('success', 'محصول در سبد خرید شما نیست');
        }
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

    private function getItem()
    {
        if (Cart::instance('shopping')->has($this->variant)) {
            return Cart::instance('shopping')->get($this->variant);
        }
        return null;
    }


    private function showAlert($type, $message)
    {
        $this->dispatchBrowserEvent('show-toast', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}
