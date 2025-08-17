<?php

namespace Modules\Front\Http\Livewire\Cart;

use Livewire\Component;

class SummeryBox extends Component
{
    protected $listeners = [
        'cart.updated' => '$refresh',
        /*'summery.updated' => '$refresh',*/
    ];

    public function render()
    {
        return view('front::livewire.cart.summery-box');
    }

}
