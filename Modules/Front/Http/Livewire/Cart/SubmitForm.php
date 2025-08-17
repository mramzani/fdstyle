<?php

namespace Modules\Front\Http\Livewire\Cart;

use Livewire\Component;

class SubmitForm extends Component
{
    protected $listeners = ['summery.updated' => '$refresh'];
    public function render()
    {
        return view('front::livewire.cart.submit-form');
    }
}
