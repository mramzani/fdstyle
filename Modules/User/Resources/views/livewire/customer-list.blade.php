<div class="card">
    <div class="card-header border-bottom d-none">
        <h5 class="card-title">فیلتر جستجو</h5>
        <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
            <div class="col-md-4 ">
                <select id="UserRole" class="form-select text-capitalize">
                    <option value=""> انتخاب نقش</option>
                    <option value="مدیر">مدیر</option>
                    <option value="مشترک">مشترک</option>
                    <option value="نویسنده">نویسنده</option>
                    <option value="نگهدارنده">نگهدارنده</option>
                    <option value="ویرایشگر">ویرایشگر</option>
                </select>
            </div>
            <div class="col-md-4 ">
                <select id="UserRole" class="form-select text-capitalize">
                    <option value=""> انتخاب نقش</option>
                    <option value="مدیر">مدیر</option>
                    <option value="مشترک">مشترک</option>
                    <option value="نویسنده">نویسنده</option>
                    <option value="نگهدارنده">نگهدارنده</option>
                    <option value="ویرایشگر">ویرایشگر</option>
                </select>
            </div>
            <div class="col-md-4 ">
                <select id="UserRole" class="form-select text-capitalize">
                    <option value=""> انتخاب نقش</option>
                    <option value="مدیر">مدیر</option>
                    <option value="مشترک">مشترک</option>
                    <option value="نویسنده">نویسنده</option>
                    <option value="نگهدارنده">نگهدارنده</option>
                    <option value="ویرایشگر">ویرایشگر</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-datatable table-responsive">
        <div class="row mx-2">
            <div class="col-md-12">
                <div
                    class="text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-row mb-3 mb-md-0">
                    <div class="d-flex justify-content-end m-3">
                        <label>
                            <input type="search" wire:model="search" class="form-control" placeholder="جستجو ...">
                        </label>
                    </div>
                    <div class="position-relative">
                        @can('customers_create')
                            <button class="dt-button add-new btn btn-primary" tabindex="0"
                                    type="button" wire:click.prevent="showAddCustomerOffCanvas">
                                    <span>
                                        <i class="bx bx-plus me-0 me-lg-2"></i>
                                        <span class="d-none d-lg-inline-block">افزودن مشتری جدید</span>
                                    </span>
                            </button>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
        <table class="table border-top table-responsive">
            @if($customers)
                <thead>
                <tr>
                    <th>نام‌و‌نام‌خانوادگی</th>
                    <th>موبایل</th>
                    <th wire:click="sortBy('status')" :direction="$sortField === 'status' ? $sortDirection : null "
                        class="cursor-pointer">وضعیت
                    </th>
                    <th class="cursor-pointer">وضعیت حساب</th>
                    <th class="cursor-pointer">منبع ثبت‌نام</th>
                    <th wire:click="sortBy('created_at')"
                        :direction="$sortField === 'created_at' ? $sortDirection : null " class="cursor-pointer">تاریخ
                        عضویت <small>  {{ $sortLabel }}  </small></th>
                    <th>اقدامات</th>
                </tr>
                </thead>
            @endif
            <tbody>
            @forelse($customers as $customer)
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
                                    <span class="fw-semibold">{{ $customer->full_name }}</span>
                                </a>
                                <small class="text-muted">{{ $customer->email ?? 'فاقد ایمیل' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="">
                                    <span class="text-truncate">
                                        {{ $customer->mobile }}
                                    </span>
                    </td>
                    <td>{!! $customer->status_for_human !!}</td>
                    <td>
                            <span data-bs-toggle="tooltip"
                                  data-color="{{ $customer->detail->due_amount > 0 ? 'warning' : 'secondary' }}"
                                  data-bs-offset="0,4" data-bs-placement="top"
                                  title="{{ $customer->detail->due_amount > 0 ? 'بدهکار' : 'بی‌حساب' }}">
                                {{ number_format($customer->detail->due_amount ?? '0') }}
                            </span>
                        تومان
                    </td>
                    <td>{!! $customer->register_type !!}</td>
                    <td>{{ verta($customer->created_at)->formatDifference()  }}</td>
                    <td>
                        <div class="d-inline-block text-nowrap">
                            @can('customers_edit')
                                <button class="btn btn-sm btn-icon" wire:click.prevent="edit({{ $customer->id }})">
                                    <i class="bx bx-edit"></i></button>
                            @endcan
                            @can('customers_delete')
                                <button class="btn btn-sm btn-icon delete-record"
                                        wire:click.prevent="deleteCustomer({{ $customer->id }})"><i
                                        class="bx bx-trash"></i></button>
                            @endcan
                            @can('customers_detail_view')
                                <a href="{{ route('dashboard.customers.show',$customer) }}"
                                   class="btn btn-sm btn-icon delete-record"><i class="bx bx-show"></i></a>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="d-flex justify-content-center align-items-center">
                            <span class="text-muted py-2">اطلاعاتی یافت نشد</span>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $customers->links() }}
    </div>
    <!-- Offcanvas to add new user -->
    @canany(['customers_create','customers_edit'])
        <div class="offcanvas offcanvas-end" tabindex="-1"
             id="addCustomerOffCanvas"
             aria-labelledby="addCustomerOffCanvasLabel" wire:ignore.self>
            <div class="offcanvas-header border-bottom">
                <h6 id="addCustomerOffCanvasLabel" class="offcanvas-title">افزودن مشتری جدید</h6>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <form class="add-new-user pt-0" id="addNewUserForm"
                      wire:submit.prevent="{{ $showEditModal ? 'updateCustomer' : 'createCustomer' }}">
                    <div class="mb-3">
                        <label class="form-label" for="first_name">نام</label>
                        <input type="text" class="form-control @error('first_name') border-danger @enderror"
                               id="first_name"
                               placeholder="نام مشتری"
                               wire:model.defer="customer.first_name">
                        @error('first_name')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="last_name">نام‌خانوادگی</label>
                        <input type="text" class="form-control" id="last_name" placeholder="نام‌خانوادگی مشتری"
                               wire:model.defer="customer.last_name">
                        @error('last_name')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="mobile">موبایل</label>
                        <input type="text" id="mobile" class="form-control"
                               placeholder="شماره موبایل مشتری"
                               wire:model.defer="customer.mobile" maxlength="11">
                        @error('mobile')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="national_code">کدملی</label>
                        <input type="text" id="national_code" maxlength="10" class="form-control"
                               wire:model.defer="customer.national_code" max="10"
                               placeholder="کدملی مشتری">
                        @error('national_code')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">ایمیل</label>
                        <input type="email" id="email" class="form-control"
                               wire:model.defer="customer.email"
                               placeholder="ایمیل مشتری">
                        @error('email')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="status">وضعیت</label>
                        <select id="status" wire:model.defer="customer.status" class="form-select">
                            @foreach(\Modules\User\Entities\User::STATUSES as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        @error('status')
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
    @endcan
</div>
