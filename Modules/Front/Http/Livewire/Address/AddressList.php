<?php

namespace Modules\Front\Http\Livewire\Address;

use App\Services\Shipping\ShippingManager;
use Livewire\Component;
use Modules\Front\Entities\Address;

class AddressList extends Component
{
    protected $listeners = ['address_added' => '$refresh'];
    public $addresses;


    public function render()
    {
        $this->addresses = auth('customer')->user()->addresses()->get();
        return view('front::livewire.address.address-list');
    }

    public function addressDefault(Address $address)
    {
        Address::resetDefaultAddress();
        $address->is_default = true;
        $address->save();

        session()->put('default_address',$address->id);

       //resolve(ShippingManager::class)->calculateShippingPrice();

        $this->emit('summery.updated');

    }
}
