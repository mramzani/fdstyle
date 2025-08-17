<?php

namespace Modules\User\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Modules\User\Entities\User;
use Modules\User\Traits\Sortable;
use phpDocumentor\Reflection\Types\Collection;
use Throwable;

class UserList extends Component
{
    use Sortable;

    public $user = [];
    public $userModel;
    public $showEditModal = false;
    protected $listeners = ['deleteConfirmed' => 'handleDelete'];
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'page' => ['except' => 1],
    ];
    public $page = 1;

    public function render()
    {
        return view('user::livewire.user-list', [
            'users' => User::searchBy($this->search, $this->sortField, $this->sortDirection)
        ]);
    }

    public function show()
    {
        $this->user = [];
        $this->showEditModal = false;
        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'User'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store()
    {
        $this->validateUser();

        \DB::beginTransaction();
        try {
            $this->user['password'] = Hash::make($this->user['password']);
            $this->user['user_type'] = 'staff_members';
            $user = User::create($this->user);
            $user->refreshRoles($this->user['role']);
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => ';کاربر با موفقیت اضافه شد'
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error creating User: ' . $exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error creating User'
            ]);
        }

    }

    public function edit(User $user)
    {
        $this->showEditModal = true;
        $user->load('roles');
        $this->userModel = $user;
        $this->user = $user->toArray();

        $this->user['role'] = $this->userModel->roles->first()->name;

        $this->dispatchBrowserEvent('show-offCanvas', [
            'offCanvas' => 'User'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update()
    {

        $this->validateUpdateUser();
        \DB::beginTransaction();
        try {
            if (array_key_exists('password', $this->user)) {
                $this->user['password'] = Hash::make($this->user['password']);
            }
            $this->userModel->update($this->user);
            $this->userModel->refreshRoles($this->user['role']);
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'اطلاعات کاربر با موفقیت ویرایش شد'
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            $this->dispatchBrowserEvent('hide-offCanvas', [
                'message' => 'Error Updating User'
            ]);
        }
    }

    public function delete(User $user)
    {
        $this->userModel = $user;
        $this->dispatchBrowserEvent('confirm-delete');
    }

    public function handleDelete()
    {
        $this->userModel->delete();

        $this->dispatchBrowserEvent('Deleted', ['message' => 'کاربر با موفقیت حذف گردید.']);

    }

    private function validateUser()
    {
        Validator::make($this->user, [
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'mobile' => 'required|unique:users,mobile|ir_mobile:zero',
            'national_code' => 'string|nullable|ir_national_code',
            'password' => ['required', Password::min(8)],
            'email' => 'email|nullable',
            'role' => 'required',
            'status' => 'string|in:' . collect(User::STATUSES)->keys()->implode(','), //output: active,deActive
        ])->validate();
    }

    private function validateUpdateUser()
    {
        Validator::make($this->user, [
            'first_name' => 'required|string|persian_alpha',
            'last_name' => 'required|string|persian_alpha',
            'mobile' => 'required|ir_mobile:zero|unique:users,mobile,' . $this->userModel->id,
            'national_code' => 'string|nullable|ir_national_code',
            'password' => [Password::min(8)],
            'email' => 'email|nullable',
            'status' => 'string|nullable',
        ])->validate();
    }
}
