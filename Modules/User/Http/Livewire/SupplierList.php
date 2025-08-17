<?php

namespace Modules\User\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\User\Entities\Supplier;
use Modules\User\Entities\User;
use Modules\User\Traits\Sortable;
use Throwable;

class SupplierList extends Component
{
    use WithPagination, Sortable;

    public $supplier = [];
    public $supplierModel;
    public $showEditModal;
    protected $listeners = ['deleteConfirmed' => 'handleDelete'];
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
        return view('user::livewire.supplier-list', [
            'suppliers' => Supplier::searchWithRelation($this->search, $this->sortField, $this->sortDirection, 'detail')
        ]);
    }

    public function show()
    {
        $this->supplier = [];
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Supplier'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store()
    {
        $this->validateSupplier();
        $this->supplier['user_type'] = 'suppliers';
        \DB::beginTransaction();
        try {
            Supplier::create($this->supplier);
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'تامین‌کننده با موفقیت اضافه شد'
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error creating Supplier: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error creating Supplier'
            ]);
        }

    }

    public function edit(Supplier $supplier)
    {
        $this->showEditModal = true;
        $this->supplierModel = $supplier->withoutRelations();
        $this->supplier = $supplier->withoutRelations()->toArray();

        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Supplier'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update()
    {
        $this->validateUpdateSupplier();
        \DB::beginTransaction();
        try {
            $this->supplierModel->update($this->supplier);
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'اطلاعات تامین‌کننده با موفقیت ویرایش شد'
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Updating Supplier: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error Updating Supplier'
            ]);
        }

    }

    public function delete(Supplier $supplier)
    {
        $this->supplierModel = $supplier;
        $this->dispatchBrowserEvent('confirm-delete');
    }

    public function handleDelete()
    {
        $this->supplierModel->delete();

        $this->dispatchBrowserEvent('Deleted', ['message' => 'تامین‌کننده با موفقیت حذف گردید.']);

    }

    private function validateSupplier(): void
    {
        Validator::make($this->supplier, [
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'mobile' => 'required|unique:users,mobile|ir_mobile:zero',
            'national_code' => 'string|nullable|ir_national_code',
            'email' => 'email|nullable',
            'status' => 'string|in:' . collect(User::STATUSES)->keys()->implode(','),
        ])->validate();
    }

    private function validateUpdateSupplier(): void
    {
        Validator::make($this->supplier, [
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'mobile' => 'required|ir_mobile:zero|unique:users,mobile,' . $this->supplierModel->id,
            'national_code' => 'string|nullable|ir_national_code',
            'email' => 'email|nullable',
            'status' => 'string|nullable',
        ])->validate();
    }
}
