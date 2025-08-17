<?php

namespace Modules\Order\Http\Livewire\Sales\Pos;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Modules\User\Entities\Customer;
use Modules\User\Entities\User;
use Throwable;

class AddCustomer extends Component
{
    public $customer = [];

    public $ignore = true;

    protected $rules = [
        'customer.first_name' => 'required|string|persian_alpha',
        'customer.last_name' => 'required|string|persian_alpha',
        'customer.mobile' => 'required|unique:users,mobile|ir_mobile:zero',
    ];

    public function render()
    {
        return view('order::livewire.sales.pos.add-customer');
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * @throws Throwable
     */
    public function createCustomer()
    {
        $validatedData = $this->validate();

        \DB::beginTransaction();
        try {

            Customer::create([
                'register_type' => "dashboard",
                'first_name' => $validatedData['customer']['first_name'],
                'last_name' => $validatedData['customer']['last_name'],
                'mobile' => $validatedData['customer']['mobile'],
            ]);
            $this->resetValidation();
            $this->reset();
            $this->dispatchBrowserEvent('hide-offCanvas');
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Creating Customer: ' . $exception->getMessage());
        }

    }

}
