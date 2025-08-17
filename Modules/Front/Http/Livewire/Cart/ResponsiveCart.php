<?php

namespace Modules\Front\Http\Livewire\Cart;

use Livewire\Component;

class ResponsiveCart extends Component
{
    protected $listeners = ['cart.updated' => '$refresh'];

    public function render()
    {
        return view('front::livewire.cart.responsive-cart');
    }
}
