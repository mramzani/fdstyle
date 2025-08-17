<?php

namespace Modules\Front\Http\Livewire\Cart;

use Livewire\Component;

class CartHeader extends Component
{
    protected $listeners = ['cart.updated' => '$refresh'];
    public function render()
    {
        return view('front::livewire.cart.cart-header');
    }

    public function delete()
    {
        //TODO: delete handle
    }
}
