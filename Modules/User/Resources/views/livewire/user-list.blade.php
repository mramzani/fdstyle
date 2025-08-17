<div class="card">
    <div class="card-datatable table-responsive">
        <div class="row mx-2">
            <div class="col-md-12">
                <div class="text-xl-end text-lg-start text-md-end text-start d-flex align-items-center
                justify-content-end flex-row mb-3 mb-md-0">
                    <div class="d-flex justify-content-end m-3">
                        <label>
                            <input type="search" class="form-control" wire:model.debounce.1000="search"
                                   placeholder="جستجو ...">
                        </label>
                    </div>
                    <div class="position-relative">
                        <button class="btn btn-label-secondary dropdown-toggle mx-3 d-none">
                                    <span>
                                        <i class="bx bx-upload me-2"></i>
                                        برون‌ریزی
                                    </span>
                        </button>
                        @can('users_create')
                            <button class="dt-button add-new btn btn-primary" wire:click="show"
                                    type="button" data-bs-toggle="offcanvas" data-bs-target="#addUserOffCanvas">
                                    <span>
                                        <i class="bx bx-plus me-0 me-lg-2"></i>
                                        <span class="d-none d-lg-inline-block">افزودن کاربر جدید</span>
                                    </span>
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        @can('users_view')
            <table class="table border-top table-responsive">
                <thead>
                <tr>
                    <th>نام‌و‌نام‌خانوادگی</th>
                    <th>موبایل</th>
                    <th wire:click="sortBy('status')" :direction="$sortField === 'status' ? $sortDirection : null "
                        class="cursor-pointer">وضعیت
                    </th>
                    <th wire:click="sortBy('created_at')"
                        :direction="$sortField === 'created_at' ? $sortDirection : null " class="cursor-pointer">تاریخ
                        عضویت <small>  {{ $sortLabel }}  </small></th>
                    <th>نقش کاربر</th>
                    <th>اقدامات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="">
                            <div class="d-flex justify-content-start align-items-center user-name">
                                <div class="avatar-wrapper d-none">
                                    <div class="avatar avatar-sm me-3"><img
                                            src="{{ asset('assets/panel/img/avatars/5.png') }}"
                                            alt="Avatar" class="rounded-circle"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="text-body text-truncate">
                                        <span class="fw-semibold">{{ $user->full_name }}</span>
                                    </a>
                                    <small class="text-muted">{{ $user->email ?? 'فاقد ایمیل' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="">
                                    <span class="text-truncate">
                                        {{ $user->mobile }}
                                    </span>
                        </td>
                        <td>{!! $user->status_for_human !!}</td>
                        <td>{{ verta($user->created_at)->formatDifference()  }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-label-secondary">{{ $role->display_name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="d-inline-block text-nowrap">
                                @can('users_edit')
                                    <button class="btn btn-sm btn-icon" data-bs-target="#addUserOffCanvas"
                                            data-bs-toggle="offcanvas" wire:click.prevent="edit({{ $user->id }})">
                                        <i class="bx bx-edit"></i></button>
                                @endcan
                                @can('users_delete')
                                    <button class="btn btn-sm btn-icon delete-record"
                                            wire:click.prevent="delete({{ $user->id }})"><i class="bx bx-trash"></i>
                                    </button>
                                @endcan

                                @can('users_detail_view')
                                    <a href="{{ route('dashboard.users.show',$user) }}"
                                       class="btn btn-sm btn-icon"><i class="bx bx-show"></i></a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endcan
    </div>
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
    <!-- Off canvas to add new user -->
    @canany('users_create','users_edit')
        <div class="offcanvas offcanvas-end" tabindex="-1" id="addUserOffCanvas"
             aria-labelledby="addUserOffCanvasLabel" wire:ignore.self>
            <div class="offcanvas-header border-bottom">
                <h6 id="addUserOffCanvasLabel" class="offcanvas-title">افزودن کاربر جدید</h6>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <form class="add-new-user pt-0" id="addNewUserForm"
                      wire:submit.prevent="{{ $showEditModal ? 'update' : 'store' }}">
                    <div class="mb-3">
                        <label class="form-label" for="first_name">نام</label>
                        <input type="text" class="form-control @error('first_name') border-danger @enderror"
                               id="first_name"
                               placeholder="نام کاربر"
                               wire:model.defer="user.first_name">
                        @error('first_name')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="last_name">نام‌خانوادگی</label>
                        <input type="text" class="form-control" id="last_name" placeholder="نام‌خانوادگی کاربر"
                               wire:model.defer="user.last_name">
                        @error('last_name')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="mobile">موبایل</label>
                        <input type="text" id="mobile" class="form-control"
                               placeholder="شماره موبایل کاربر"
                               wire:model.defer="user.mobile" maxlength="11">
                        @error('mobile')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="national_code">کدملی</label>
                        <input type="text" id="national_code" maxlength="10" class="form-control"
                               wire:model.defer="user.national_code" max="10"
                               placeholder="کدملی کاربر">
                        @error('national_code')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">ایمیل</label>
                        <input type="email" id="email" class="form-control"
                               wire:model.defer="user.email"
                               placeholder="ایمیل کاربر">
                        @error('email')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">رمزعبور</label>
                        <input type="password" id="password" class="form-control"
                               wire:model.defer="user.password"
                               placeholder="رمزعبور">
                        @error('password')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="status">وضعیت</label>
                        <select id="status" wire:model.defer="user.status" class="form-select">
                            @foreach(\Modules\User\Entities\User::STATUSES as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        @error('status')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">

                        <label class="form-label" for="role">نقش کاربر</label>
                        <select id="role" wire:model.defer="user.role" class="form-select">
                            <option value="">انتخاب نقش کاربر</option>
                            @foreach(\Modules\Role\Entities\Role::all()->pluck('name','display_name') as $display_name => $name)
                                @if($name != 'technicalAdmin')
                                    <option value="{{ $name }}">{{ $display_name }}</option>
                                @endif
                            @endforeach
                        </select>

                        @error('role')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">
                        @if($showEditModal)
                            <span>بروزرسانی</span>
                        @else
                            <span>ایجاد</span>
                        @endif
                    </button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">انصراف</button>
                </form>
            </div>
        </div>
    @endcanany
</div>


