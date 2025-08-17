@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش چیدمان')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.css') }}">
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <form action="{{ route('dashboard.home.update',$home->id) }}" method="post">
            @csrf
            <div class="row">
                @include('dashboard::partials.alert')
                <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label input-required" for="name">نام صفحه</label>
                                <input type="text" class="form-control @error('name') border-danger @enderror"
                                       value="{{ old('name',$home->name) }}" id="name" name="name"
                                       placeholder="نام صفحه به دلخواه وارد کنید">
                                @error('name')
                                @include('dashboard::partials.validation-error')
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label input-required" for="start_at">تاریخ شروع نمایش</label>
                                    <input type="text"
                                           class="form-control flatpickr-datetime @error('start_at') border-danger @enderror"
                                           value="{{ old('start_at',verta($home->start_at)->format('Y-n-j H:i')) }}"
                                           id="start_at" name="start_at">
                                    @error('start_at')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label input-required" for="expire_at">تاریخ پایان نمایش</label>
                                    <input type="text"
                                           class="form-control flatpickr-datetime @error('expire_at') border-danger @enderror"
                                           value="{{ old('expire_at',verta($home->expire_at)->format('Y-n-j H:i')) }}"
                                           id="expire_at" name="expire_at">
                                    @error('expire_at')
                                    @include('dashboard::partials.validation-error')
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-4 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">وضعیت</label>
                                <select class="form-select" id="status" name="status">
                                    @foreach(\Modules\PageBuilder\Entities\Home::STATUS as $key => $value)
                                        <option value="{{ $key }}"
                                                @if($key == $home->status) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-check my-3">
                                <input class="form-check-input" type="checkbox"
                                       @if($home->is_default == 1) checked @endif
                                       name="is_default" value="1" id="is_default">
                                <label class="form-check-label" for="is_default">چیدمان پیشفرض</label>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100 mb-3">
                                <span
                                    class="d-flex align-items-center justify-content-center text-nowrap">بروزرسانی</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        <div class="row mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-elements">
                        <span class="me-2">آیتم‌های صفحه</span>
                        <div class="card-header-elements ms-auto">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addHomeItem">
                                افزودن آیتم جدید
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            @if ($errors->any())
                                <div class="alert alert-warning">
                                    <span>لطفا خطاهای زیر را بررسی نمایید</span>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li class="list-inline">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        @if(count($home->items) == 0)
                            <div class="alert alert-warning">
                                برای این صفحه تاکنون آیتم ایجاد نشده است.
                            </div>
                        @else
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>ردیف</th>
                                        <th>عنوان</th>
                                        <th>نوع آیتم</th>
                                        <th>مشخصات آیتم</th>
                                        <th>ترتیب نمایش</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    @foreach($home->items()->orderBy('priority','desc')->get() as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1}}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ \Modules\PageBuilder\Entities\HomeItem::ROWABLE_TYPE[$item->rowable_type] }}</td>
                                            <td>
                                                @if($item->rowable instanceof \Modules\Category\Entities\Category)
                                                    {{ $item->rowable->title_fa }}
                                                @elseif($item->rowable instanceof \Modules\PageBuilder\Entities\Offers)
                                                    {{ $item->rowable->title }}
                                                @else
                                                    {{ $item->rowable->name ?? '' }}
                                                @endif
                                            </td>
                                            <td>{{ $item->priority }}</td>
                                            <td>
                                                <div class="d-inline-block text-nowrap">
                                                    <form
                                                        action="{{ route('dashboard.home-item.destroy',$item->id) }}"
                                                        method="post" id="homeItemDeleteForm-{{ $item->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                data-id="{{ $item->id }}"
                                                                class="btn btn-sm btn-icon delete-home-item">
                                                            <i class="bx bx-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addHomeItem" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title secondary-font" id="modalCenterTitle">افزودن آیتم جدید</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('dashboard.home-item.store',$home->id) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info">درصورت ایجاد چند اسلایدر تنها اسلایدر، با الویت بالاتر نمایش
                                داده می‌شود.
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="title" class="form-label">عنوان</label>
                                    <input type="text" id="title" class="form-control" name="title"
                                           placeholder="عنوان را وارد کنید">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="item_type" class="form-label">نوع آیتم را انتخاب کنید</label>
                                    <select class="form-control" name="item_type" id="item_type"
                                            onchange="changeItemType(event)">
                                        <option value="" disabled selected>انتخاب نوع آیتم</option>
                                        @foreach(\Modules\PageBuilder\Entities\HomeItem::ITEM_TYPE as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row" id="rowableSection">
                                <div class="col mb-3">
                                    <label for="rowable_id" class="form-label">آیتم را انتخاب کنید</label>
                                    <select class="form-control" name="rowable_id" id="rowable_id">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="priority" class="form-label">الویت</label>
                                    <input type="text" id="priority" class="form-control" name="priority"
                                           placeholder="الویت را وارد کنید">
                                    <small>فقط عدد وارد کنید | عدد بزرگتر نشانه‌ی الویت بالاتر می‌باشد.</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                بستن
                            </button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/jdate/jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/flatpickr-jdate.js') }}"></script>
    <script src="{{ asset('assets/panel/vendor/libs/flatpickr/l10n/fa-jdate.js') }}"></script>
    <script type="text/javascript">
        let flatpickrDatetime = $(".flatpickr-datetime");
        if (flatpickrDatetime) {
            flatpickrDatetime.flatpickr({
                enableTime: true,
                locale: 'fa',
                altInput: true,
                altFormat: 'Y/m/d - H:i',
                disableMobile: true,
            });
        }

        let changeItemType = (event) => {
            let valueBox = $(`select[name='rowable_id']`);
            let item_type = event.target.value;

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('dashboard.homes.getItem') }}',
                contentType: "application/json;charset=utf-8",
                data: JSON.stringify({
                    item_type
                }),

                success: function (res) {

                    if (item_type == 'banner' || item_type == 'slider' || item_type == 'offer') {
                        valueBox.html(`
                        <option value="" disabled>انتخاب کنید</option>
                        ${
                            res.map(function (value) {
                                return `<option value="${value['id']}">
                                        ${value['name']} ${value['type']}
                        </option>`
                            })
                        }
                    `);
                    }else {
                        valueBox.html(res);
                    }

                }
            });

        }

        $(".delete-home-item").on('click', function (event) {
            event.preventDefault();
            const btn = $(this);
            let id = $(this).data("id");
            Swal.fire({
                title: "@lang('dashboard::common.Are you sure to delete?')",
                text: "@lang('dashboard::common.If you delete the information, it will be lost!')",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "@lang('dashboard::common.confirm')",
                cancelButtonText: "@lang('dashboard::common.cancel')",
                buttonsStyling: false,
            }).then(function (value) {
                if (!value.dismiss) {
                    $("#homeItemDeleteForm-" + id).submit();
                }
            });
        });
    </script>
@endsection
