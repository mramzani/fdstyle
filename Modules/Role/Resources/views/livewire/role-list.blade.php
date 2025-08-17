<div>

    <!-- Role list -->
    <div class="card">
        <div class="card-body">
            <div class="card-header header-elements">
                <h4 class="d-flex align-items-center">
                 <span class="badge bg-label-secondary p-2 rounded me-3">
                     <i class="bx bx-cube bx-sm"></i>
                 </span>
                    @lang('dashboard::common.roles permissions')
                </h4>

                <div class="card-header-elements ms-auto">
                    <button class="btn btn-primary" type="button" wire:click.prevent="show">
                        @lang('role::roles.add new role')
                    </button>
                </div>
            </div>
            @include('dashboard::partials.alert')

            @if($roles->isEmpty())
                <div class="alert alert-warning alert-dismissible d-flex align-items-center" role="alert">
                    <i class="bx bx-xs bx-message-alt-error me-2"></i>
                    @lang('role::roles.Unfortunately, no role has been defined in the system yet!')
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table warehouse-list">
                        <thead class="table-dark">
                        <tr>
                            <th>@lang('dashboard::common.name')</th>
                            <th>@lang('dashboard::common.display_name')</th>
                            <th>@lang('dashboard::common.operation')</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->display_name }}</td>
                                <td>
                                    @if($role->id === 1)
                                        @role('technicalAdmin')
                                        <div class="btn-group">
                                            @can('roles_edit')
                                                <button
                                                    class="btn btn-sm btn-outline-primary btn-group"
                                                    wire:click.prevent="edit({{ $role }})">@lang('dashboard::common.edit')
                                                </button>
                                            @endcan
                                            @can('roles_delete')
                                                <button type="submit" wire:click.prevent="showDelete({{ $role }})"
                                                        class="btn btn-sm
                                                    btn-outline-danger">@lang('dashboard::common.delete')
                                                </button>
                                            @endcan
                                        </div>
                                        @endrole
                                    @else
                                        <div class="btn-group">
                                            @can('roles_edit')
                                                <button
                                                    class="btn btn-sm btn-outline-primary btn-group"
                                                    wire:click.prevent="edit({{ $role }})">@lang('dashboard::common.edit')
                                                </button>
                                            @endcan
                                            @can('roles_delete')

                                                <button type="submit" wire:click.prevent="showDelete({{ $role }})"
                                                        class="btn btn-sm
                                                    btn-outline-danger">@lang('dashboard::common.delete')
                                                </button>

                                            @endcan
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @canany(['roles_create','roles_edit'])
        <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="addRoleOffCanvas"
             aria-labelledby="addRoleOffCanvasLabel" wire:ignore.self>
            <div class="offcanvas-header">
                <h5 id="addRoleOffCanvasLabel" class="offcanvas-title">
                    @if($showEditModal)
                        <span>@lang('role::roles.edit role')</span>
                    @else
                        <span>@lang('role::roles.add new role')</span>
                    @endif
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>

            <div class="offcanvas-body my-3 mx-0 flex-grow-0">

                <form autocomplete="off" wire:submit.prevent="{{ $showEditModal ? 'updateRole' : 'storeRole' }}">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">@lang('dashboard::common.name')</label>
                                <input type="text" wire:model.defer="role.name" class="form-control
                                @error('name') border-danger @enderror"
                                       id="name" placeholder="@lang('dashboard::common.name')">
                                @error('name')
                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label"
                                       for="display_name">@lang('dashboard::common.display_name')</label>
                                <input type="text" wire:model.defer="role.display_name" class="form-control
                        @error('display_name') is-invalid @enderror"
                                       id="display_name"
                                       placeholder="@lang('dashboard::common.display_name')">
                                @error('display_name')
                                <div class="d-block invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-10 ">
                            <div class="checkbox text-small">
                                <input type="checkbox"
                                       class="form-check-input"
                                       wire:model="selectPermission"
                                       id="checkAll">
                                <label class="form-check-label"
                                       for="checkAll">@lang('dashboard::common.select all')</label>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Permission table -->
                            <div class="table-responsive">
                                <table class="table table-flush-spacing">
                                    <tbody>
                                    @forelse($permissions->chunk(4) as $chunk)
                                        <tr>
                                            @foreach($chunk as $permission)
                                                <td>
                                                    <div class="checkbox text-small">
                                                        <input
                                                            type="checkbox"
                                                            value="{{ $permission->name }}"
                                                            wire:model="permissionList"
                                                            class="form-check-input permission"
                                                            id="{{ $permission->name }}"
                                                            @if($showEditModal)
                                                                @if(in_array($permission->name,$roleModel->permissions->pluck('name')->toArray())) checked @endif
                                                            @endif
                                                        >
                                                        <label class="form-check-label"
                                                               for="{{ $permission->name }}">{{ $permission->display_name }} </label>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <div class="alert alert-primary alert-dismissible mb-2 col-10 offset-1">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-error"></i>
                                                <span>
                                                @lang('dashboard.pages.permission.there are not any permission')
                                            </span>
                                            </div>
                                        </div>
                                    @endforelse
                                    <tbody>
                                </table>
                            </div>
                            <!-- Permission table -->
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary mb-2 d-grid w-100">
                        @if($showEditModal)
                            <span>@lang('role::roles.edit role')</span>
                        @else
                            <span>@lang('role::roles.add new role')</span>
                        @endif

                    </button>
                    <button type="button" class="btn btn-label-secondary d-grid w-100"
                            data-bs-dismiss="offcanvas">
                        @lang('dashboard::common.cancel')
                    </button>
                </form>
            </div>
        </div>
    @endcanany
    <!-- /Role list -->
</div>
