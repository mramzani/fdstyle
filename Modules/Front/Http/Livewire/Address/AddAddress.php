<?php

namespace Modules\Front\Http\Livewire\Address;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Dashboard\Entities\City;
use Modules\Dashboard\Entities\Province;
use Modules\Front\Entities\Address;

class AddAddress extends Component
{
    public $provinces;
    public $province;
    public $cities = [] ;
    public $city;
    public $mobile;
    public $address;
    public $plaque;
    public $postal_code;
    public $readonly = '';
    public bool $transfereeCheck = false;
    public string $full_name = '';

    public function render()
    {
        $this->provinces = Province::all();
        return view('front::livewire.address.add-address');
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string',
            'mobile' => 'required|string',
            'province' => 'required',
            'cities' => 'array',
            'city' => 'required',
            'address' => 'required',
            'plaque' => 'required',
            'postal_code' => 'required',
        ];
    }

    public function transfereeSelf()
    {
        if ($this->transfereeCheck){
            $this->full_name = \Auth::guard('customer')->user()->full_name;
            $this->mobile = \Auth::guard('customer')->user()->mobile;
            $this->readonly = 'readonly';
        }else{
            $this->full_name = '';
            $this->mobile = '';
            $this->readonly = '';
        }
    }

    public function changeProvince()
    {
        $this->cities = City::query()->where('province_id',$this->province)->get();
    }

    public function addAddress()
    {
        $this->validate();
        $add = Address::create([
            'user_id' => auth()->guard('customer')->user()->id,
            'city_id' => $this->city,
            'transferee' => $this->full_name,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'plaque' => $this->plaque,
            'postal_code' => $this->postal_code,
        ]);
        $this->emit('address_added');
        $this->emit('summery.updated');
        $this->closeModal();

    }
    private function closeModal()
    {
        $this->dispatchBrowserEvent('close-modal');
    }
}
