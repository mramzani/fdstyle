@extends('dashboard::layouts.master')
@section('dashboardTitle','لیست تنظیم موجودی')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card">
                <div class="m-2">
                    @include('dashboard::partials.alert')
                </div>
                <div class="card-header header-elements">
                    <span class="me-2">لیست تنظیم موجودی</span>
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('adjustments.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon bx bx-plus bx-xs"></span> تنظیم موجودی جدید
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @can('stock_adjustments_view')
                        <div class="table-responsive text-nowrap">
                            @if($stockAdjustments->count() > 0)
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>محصول</th>
                                        <th>انبار</th>
                                        <th>تعداد</th>
                                        <th>افزایش/کاهش</th>
                                        <th>توسط</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($stockAdjustments as $key => $stockAdjustment)
                                        <tr>
                                            <td>{{ $key +1  }}</td>
                                            <td>{{ $stockAdjustment->product->name }}
                                                {{ $stockAdjustment->ProductVariant ? $stockAdjustment->ProductVariant->option->valuable->title : '' }}
                                            </td>
                                            <td>{{ $stockAdjustment->warehouse->name }}</td>
                                            <td>{{ $stockAdjustment->quantity }}</td>
                                            <td>{!! $stockAdjustment->adjustment_type_for_human !!}</td>
                                            <td>{{ $stockAdjustment->createdBy->full_name }}</td>
                                            <td>
                                                <div class="d-inline-block text-nowrap">
                                                    <form action="{{ route('adjustments.destroy',$stockAdjustment->id) }}"
                                                          id="deleteAdjustmentConfirm-{{ $stockAdjustment->id }}"
                                                          method="post" class="btn-group">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-icon
                                                    delete-adjustment"
                                                                data-id="{{ $stockAdjustment->id }}"><i
                                                                class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center my-2">
                                    {{ $stockAdjustments->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">تاکنون تنظیم موجودی ثبت نشده است.</div>
                            @endif
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(".delete-adjustment").click(function (event) {
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
                if (value.isConfirmed){
                    $("#deleteAdjustmentConfirm-" + id).submit();
                }
            });
        });
    </script>
@endsection
