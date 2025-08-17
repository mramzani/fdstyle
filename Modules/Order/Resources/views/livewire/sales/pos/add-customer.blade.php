<div class="col-12 col-lg-12">
    <button class="btn btn-secondary" type="button" id="add-customer" data-bs-toggle="offcanvas"
            data-bs-target="#addCustomerOffCanvas">
            <span class="tf-icon bx bx-user-plus"></span>
        افزودن مشتری
    </button>
    <!-- Add Customer Sidebar -->
    <div wire:ignore.self class="offcanvas offcanvas-end" id="addCustomerOffCanvas" aria-hidden="true">
        <div class="offcanvas-header border-bottom">
            <h6 class="offcanvas-title">افزودن مشتری</h6>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <div class="mb-3">
                <label class="form-label" for="first_name">نام</label>
                <input type="text" class="form-control @error('customer.first_name') border-danger @enderror"
                       id="first_name"
                       placeholder="نام مشتری"
                       wire:model.defer="customer.first_name">
                @error('customer.first_name')
                <div class="d-block invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="last_name">نام‌خانوادگی</label>
                <input type="text" class="form-control @error('customer.last_name') border-danger @enderror"
                       id="last_name" placeholder="نام‌خانوادگی مشتری"
                       wire:model.defer="customer.last_name">
                @error('customer.last_name')
                <div class="d-block invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="mobile">موبایل</label>
                <input type="text" id="mobile" class="form-control @error('customer.mobile') border-danger @enderror"
                       placeholder="شماره موبایل مشتری"
                       wire:model.defer="customer.mobile" maxlength="11">
                @error('customer.mobile')
                <div class="d-block invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="button" wire:click.prevent="createCustomer" class="btn btn-primary me-sm-3 me-1 data-submit">
                <span>ایجاد</span>
            </button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">انصراف</button>


        </div>
    </div>
    <!-- /Add Customer Sidebar -->

    @once
        <script>
            window.addEventListener('hide-offCanvas', event => {
                $('#addCustomerOffCanvas').offcanvas('hide');
                Swal.fire({
                    title: 'چقدر عالی',
                    text: 'مشتری جدید با موفقیت اضافه شد!',
                    type: 'success',
                    icon: 'success',
                    timer: 3000,
                    allowOutsideClick: true,
                    backdrop: true,
                    confirmButtonText: 'فهمیدم!',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                })
            });
        </script>
    @endonce

</div>
