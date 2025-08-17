<?php

namespace Modules\Role\Http\Livewire;

use App\Services\Common;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Modules\Role\Entities\Permission;
use Modules\Role\Entities\Role;
use Throwable;

class RoleList extends Component
{

    public $role = [];
    public $roleModel;
    public $showEditModal = false;
    public $permissionList = [];
    public $selectPermission = false;

    protected $listeners = ['deleteConfirmed' => 'handelDeleteRole'];

    public function render()
    {
        $roles = Role::latest()->paginate(10);
        $permissions = Permission::all();

        return view('role::livewire.role-list', [
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function show()
    {
        $this->role = [];
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Role'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function storeRole()
    {
        $validatedData = $this->validateStoreRole();
        \DB::beginTransaction();
        try {
            $role = Role::create($validatedData);
            $role->givePermissionTo($this->permissionList);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error storing role: ' . $exception->getMessage());

            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error storing role'
            ]);
        }
        $this->dispatchBrowserEvent('hide-offCanvas', [
            'message' => 'نقش کاربری جدید با موفقیت اضافه شد'
        ]);
    }

    public function edit(Role $role)
    {
        $this->showEditModal = true;
        $this->roleModel = $role->load('permissions');
        $this->permissionList = $this->roleModel->permissions->pluck('name')->toArray();

        $this->role = $role->toArray();
        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'Role'
        ]);

    }

    /**
     * @throws Throwable
     */
    public function updateRole()
    {
        $validatedData = $this->validateUpdateRole();
        \DB::beginTransaction();
        try {
            //update role
            $this->roleModel->update([
                'display_name' => $validatedData['display_name']
            ]);
            //refresh permission
            $this->roleModel->refreshPermissions($this->permissionList);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error updating role: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error updating role'
            ]);
        }
        $this->dispatchBrowserEvent('hide-offCanvas', [
            'message' => 'نقش کاربری با موفقیت ویرایش شد'
        ]);
    }

    public function showDelete(Role $role)
    {
        $this->roleModel = $role;
        $this->dispatchBrowserEvent('confirm-delete', [
            'offCanvas' => 'Role'
        ]);
    }

    public function handelDeleteRole()
    {
        $this->roleModel->delete();
        $this->dispatchBrowserEvent('Deleted', ['message' => 'نقش با موفقیت حذف شد']);
    }

    private function validateStoreRole(): array
    {
        return Validator::make($this->role, [
            'name' => 'required|unique:roles,name',
            'display_name' => 'required',
        ])->validate();
    }

    private function validateUpdateRole(): array
    {
        return Validator::make($this->role, [
            'name' => 'required|unique:roles,name,' . $this->roleModel->id,
            'display_name' => 'required',
        ])->validate();
    }

    public function updatedSelectPermission($value)
    {
        if ($value) {
            $this->permissionList = Permission::pluck('name')->toArray();
        } else {
            $this->permissionList = [];
        }
    }

    public function updatedPermissionList()
    {
        $this->selectPermission = false;

    }
}
