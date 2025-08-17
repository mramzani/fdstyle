<?php

namespace Modules\User\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\User\Entities\Customer;
use Modules\User\Entities\User;
use Modules\User\Traits\Sortable;
use Throwable;

class CustomerList extends Component
{
    use WithPagination, Sortable;

    public $customer = [];
    public $customerModel;
    public $showEditModal = false;
    protected $listeners = ['deleteConfirmed' => 'handleDeleteCustomer'];
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'page' => ['except' => 1],
        /*'search' => ['except' => ''],
        'sortField',
        'sortDirection'*/
    ];
    public $page = 1;

    public function render()
    {
        return view('user::livewire.customer-list', [
            'customers' => Customer::searchBy($this->search, $this->sortField, $this->sortDirection)
        ]);
    }

    public function showAddCustomerOffCanvas()
    {
        $this->customer = [];
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Customer'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function createCustomer()
    {
        $validatedData = $this->validateCustomer();
        \DB::beginTransaction();
        try {
            $validatedData['user_type'] = 'customer';
            Customer::create($validatedData);
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'مشتری با موفقیت اضافه شد'
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Creating Customer: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error Creating Customer'
            ]);
        }

    }

    public function edit(Customer $customer)
    {
        $this->showEditModal = true;
        $this->customerModel = $customer->withoutRelations();
        $this->customer = $customer->withoutRelations()->toArray();

        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Customer'
        ]);
    }

    public function deleteCustomer(Customer $customer)
    {
        $this->customerModel = $customer;
        $this->dispatchBrowserEvent('confirm-delete');
    }

    public function handleDeleteCustomer()
    {
        $this->customerModel->delete();
        $this->dispatchBrowserEvent('Deleted', ['message' => 'مشتری با موفقیت حذف گردید.']);
    }

    /**
     * @throws Throwable
     */
    public function updateCustomer()
    {
        // update validate
        $this->validateUpdateCustomer();
        // update customer
        \DB::beginTransaction();
        try {
            $this->customerModel->update($this->customer);
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'اطلاعات مشتری با موفقیت ویرایش شد'
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Updating Customer: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error Updating Customer'
            ]);
        }
    }

    /**
     * Validate Customer for STORE
     * @return array
     */
    private function validateCustomer(): array
    {
        return Validator::make($this->customer, [
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'mobile' => 'required|unique:users,mobile|ir_mobile:zero',
            'national_code' => 'string|nullable|ir_national_code',
            'email' => 'email|nullable',
            'status' => 'string|in:' . collect(User::STATUSES)->keys()->implode(','),
        ])->validate();
    }

    private function validateUpdateCustomer(): void
    {
        Validator::make($this->customer, [
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'mobile' => 'required|ir_mobile:zero|unique:users,mobile,' . $this->customerModel->id,
            'national_code' => 'string|nullable|ir_national_code',
            'email' => 'email|nullable',
            'status' => 'string|nullable',
        ])->validate();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
