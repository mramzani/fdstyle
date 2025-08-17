@extends('dashboard::layouts.master')
@section('dashboardTitle','مدیریت راهنمای سایز')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card">
                <div class="m-2">
                    @include('dashboard::partials.alert')
                </div>
                @can('size_guide_create')
                    <div class="card-header header-elements">
                        <span class="me-2">لیست راهنمای سایز</span>
                        <div class="card-header-elements ms-auto">
                            <a href="{{ route('guide-size.create') }}" class="btn btn-sm btn-primary">
                                <span class="tf-icon bx bx-plus bx-xs"></span>ایجاد راهنمای سایز جدید
                            </a>
                        </div>
                    </div>
                @endcan

                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        @if($size_guides->count() > 0)
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>عنوان</th>
                                    <th>برند</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($size_guides as $key => $size_guide)
                                    <tr>
                                        <td>{{ $key + 1  }}</td>
                                        <td>{{ $size_guide->title }}</td>
                                        <td><span class="badge bg-label-info">{{ $size_guide->brand->title_fa }}</span>
                                        </td>
                                        <td>
                                            <div class="d-inline-block text-nowrap">
                                                @can('size_guide_edit')
                                                    <a href="{{ route('guide-size.edit',$size_guide->id) }}"
                                                       class="btn btn-sm btn-icon">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('size_guide_delete')
                                                    <form action="{{ route('guide-size.destroy',$size_guide->id) }}"
                                                          id="deleteSizeGuideConfirm-{{ $size_guide->id }}"
                                                          method="post" class="btn-group">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-icon delete-size-guide"
                                                                data-id="{{ $size_guide->id }}"><i
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
                            <div class="d-flex justify-content-center my-2">
                                {{ $size_guides->links() }}
                            </div>
                        @else
                            <div class="alert alert-info">تاکنون راهنمای سایز ثبت نشده است.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $(".delete-size-guide").click(function (event) {
                event.preventDefault();
                const btn = $(this);
                let id = btn.data("id");
                Swal.fire({
                    title: "@lang('dashboard::common.Are you sure to delete?')",
                    text: "@lang('dashboard::common.If you delete the information, it will be lost!')",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "@lang('dashboard::common.confirm')",
                    cancelButtonText: "@lang('dashboard::common.cancel')",
                }).then(function (value) {
                    if (value.isConfirmed) {
                        $("#deleteSizeGuideConfirm-" + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
