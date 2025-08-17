@extends('dashboard::layouts.master')
@section('dashboardTitle','ویرایش تنظیم موجودی')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="card my-1">
                @can('stock_adjustments_edit')
                    <form action="{{ route('adjustments.store') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>نام محصول</th>
                                    <th>کد محصول</th>
                                    <th>تعداد</th>
                                    <th>نوع</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                <tr>
                                    <td>
                                        {{ $stockAdjustment->product->name  }}
                                        <input type="hidden" value="{{ $stockAdjustment->product->id }}" name="product_id">
                                        <input type="hidden" value="{{ $stockAdjustment->variant_id  ?? null }}" name="variant_id">
                                    </td>
                                    <td>
                                        {{ $stockAdjustment->product->code }}
                                        <input type="hidden" value="{{ $stockAdjustment->product->code }}" name="code">
                                    </td>
                                    <td><input type="text" class="form-control" value="1" name="quantity"></td>
                                    <td>
                                        <select class="form-control" name="action">
                                            <option value="add">افزودن</option>
                                            <option value="subtract">کاهش</option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="alert alert-info">ارسال</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
@endsection

