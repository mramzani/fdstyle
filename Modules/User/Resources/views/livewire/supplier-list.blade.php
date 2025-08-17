<div class="card">
    <div class="card-datatable table-responsive">
        <div class="row mx-2">
            <div class="col-md-12">
                <div
                    class="text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-row mb-3 mb-md-0">
                    <div class="d-flex justify-content-end m-3">
                        <label>
                            <input type="search" class="form-control" wire:model.debounce.1000="search"
                                   placeholder="جستجو ...">
                        </label>
                    </div>
                    <div class="position-relative">
                        @can('suppliers_create')
                            <button class="dt-button add-new btn btn-primary" tabindex="0"
                                    type="button" wire:click.prevent="show">
                                    <span>
                                        <i class="bx bx-plus me-0 me-lg-2"></i>
                                        <span class="d-none d-lg-inline-block">افزودن تامین‌کننده جدید</span>
                                    </span>
                            </button>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
        @can('suppliers_view')
            <table class="table border-top table-responsive">
                <thead>
                <tr>
                    <th>نام‌و‌نام‌خانوادگی</th>
                    <th>موبایل</th>
                    <th wire:click="sortBy('status')" :direction="$sortField === 'status' ? $sortDirection : null "
                        class="cursor-pointer">وضعیت
                    </th>
                    <th>موجودی</th>
                    <th wire:click="sortBy('created_at')"
                        :direction="$sortField === 'created_at' ? $sortDirection : null " class="cursor-pointer">تاریخ
                        عضویت <small>  {{ $sortLabel }}  </small></th>
                    <th>اقدامات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($suppliers as $supplier)
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
                                        <span class="fw-semibold">{{ $supplier->full_name }}</span>
                                    </a>
                                    <small class="text-muted">{{ $supplier->email ?? 'فاقد ایمیل' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="">
                                    <span class="text-truncate">
                                        {{ $supplier->mobile }}
                                    </span>
                        </td>
                        <td>{!! $supplier->status_for_human !!}</td>
                        <td alt="بدهکار یا بستانکار بودن تامین‌کننده باید مشخص شود">
                            {{ number_format($supplier->detail->due_amount) }}
                            تومان
                        </td>
                        <td>{{ verta($supplier->created_at)->formatDifference()  }}</td>
                        <td>
                            <div class="d-inline-block text-nowrap">
                                @can('suppliers_edit')
                                    <button class="btn btn-sm btn-icon" wire:click.prevent="edit({{ $supplier->id }})">
                                        <i class="bx bx-edit"></i></button>
                                @endcan
                                @can('suppliers_delete')
                                    <button class="btn btn-sm btn-icon delete-record"
                                            wire:click.prevent="delete({{ $supplier->id }})"><i class="bx bx-trash"></i>
                                    </button>
                                @endcan

                                @can('suppliers_detail_view')
                                    <a href="{{ route('dashboard.suppliers.show',$supplier) }}"
                                       class="btn btn-sm btn-icon delete-record"><i class="bx bx-show"></i></a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        @endcan
    </div>
    <div class="d-flex justify-content-center" wire:ignore>
        {{ $suppliers->links() }}
    </div>
    <!-- Offcanvas to add new user -->
    @canany('suppliers_create','suppliers_edit')
        <div class="offcanvas offcanvas-end" tabindex="-1" id="addSupplierOffCanvas"
             aria-labelledby="addSupplierOffCanvasLabel" wire:ignore.self>
            <div class="offcanvas-header border-bottom">
                <h6 id="addSupplierOffCanvasLabel" class="offcanvas-title">افزودن تامین‌کننده جدید</h6>
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
                               placeholder="نام تامین‌کننده"
                               wire:model.defer="supplier.first_name">
                        @error('first_name')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="last_name">نام‌خانوادگی</label>
                        <input type="text" class="form-control" id="last_name" placeholder="نام‌خانوادگی تامین‌کننده"
                               wire:model.defer="supplier.last_name">
                        @error('last_name')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="mobile">موبایل</label>
                        <input type="text" id="mobile" class="form-control"
                               placeholder="شماره موبایل تامین‌کننده"
                               wire:model.defer="supplier.mobile" maxlength="11">
                        @error('mobile')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="national_code">کدملی</label>
                        <input type="text" id="national_code" maxlength="10" class="form-control"
                               wire:model.defer="supplier.national_code" max="10"
                               placeholder="کدملی تامین‌کننده">
                        @error('national_code')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">ایمیل</label>
                        <input type="email" id="email" class="form-control"
                               wire:model.defer="supplier.email"
                               placeholder="ایمیل تامین‌کننده">
                        @error('email')
                        <div class="d-block invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="status">وضعیت</label>
                        <select id="status" wire:model.defer="supplier.status" class="form-select">
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
