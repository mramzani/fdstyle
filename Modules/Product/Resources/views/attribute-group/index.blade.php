@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت گروه ویژگی‌ها')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-center">
            <div class="m-2">
                @include('dashboard::partials.alert')
            </div>
            <div class="col-lg-12">
                <div class="card my-2">
                    <div class="card-header bg-primary text-white py-2">مدیریت گروه‌بندی ویژگی‌ها</div>
                    <div class="card-body my-2">
                        @can('attribute_group_create')
                            <form method="POST" action="{{ route('attribute-group.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-lg-3">
                                        <label for="title" class="form-label">عنوان گروه ویژگی</label>
                                        <input type="text" name="title" id="title" class="form-control">
                                        @error('title')
                                        @include('dashboard::partials.validation-error')
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-3 mt-4">
                                        <button type="submit" class="btn btn-secondary">ایجاد گروه جدید</button>
                                    </div>
                                </div>
                            </form>
                        @endcan

                        @can('attribute_group_view')
                            <table class="table border-top table-responsive my-2">
                                <thead>
                                <tr>
                                    <th class="w-25">عنوان گروه</th>
                                    {{--<th class="w-25">دسته‌بندی</th>--}}
                                    <th class="w-50">@lang('dashboard::common.operation')</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($attribute_groups as $attribute_group)
                                    <tr>
                                        <td><span
                                                class="badge bg-label-warning">{{ $attribute_group->title ?? '' }}</span>
                                        </td>
                                        {{--<td><span class="badge bg-label-success">{{ $attribute_group->category->title_fa ?? '' }}</span></td>--}}
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                @can('attribute_group_edit')
                                                    <a href="{{ route('attribute-group.edit',$attribute_group->id) }}"
                                                       class="btn btn-sm btn-icon">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('attribute_group_delete')
                                                    <form
                                                        action="{{ route('attribute-group.destroy',$attribute_group->id) }}"
                                                        id="deleteAttributeConfirm-{{ $attribute_group->id }}"
                                                        method="post" class="btn-group">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-icon
                                                            delete-attribute-group"
                                                                data-id="{{ $attribute_group->id }}"><i
                                                                class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(".delete-attribute-group").on('click', function (event) {
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
                    $("#deleteAttributeConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
