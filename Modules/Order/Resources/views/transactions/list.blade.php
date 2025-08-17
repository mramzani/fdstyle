@extends('dashboard::layouts.master')
@section('dashboardTitle','لیست تراکنش‌های آنلاین')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xl-12">
                <h6 class="text-muted">لیست تراکنش‌های آنلاین</h6>
                @include('dashboard::partials.alert')
                <div class="card shadow-none bg-transparent">
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="row d-none">
                            <div class="col-12 col-md-4 mb-3">
                                <label for="customer" class="form-label">نام مشتری</label>
                                <input type="text" id="customer" wire:model.debounce.500="filters.customer"
                                       class="form-control">
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label for="invoice_number" class="form-label">شماره فاکتور</label>
                                <input type="text" id="invoice_number" wire:model.debounce.500="filters.invoice_number"
                                       class="form-control">
                            </div>

                        </div>
                        <!-- Search Box -->
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-tabs-line-card-active" role="tabpanel">
                                @if(count($payments) == 0)
                                    <div class="alert alert-warning">چیزی پیدا نشد!</div>
                                @else
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-hover">
                                            <thead class="table-dark">
                                            <tr>
                                                <th>تاریخ پرداخت</th>
                                                <th>شماره سفارش</th>
                                                <th>شماره پرداخت</th>
                                                <th>پیگیری بانک</th>
                                                <th>مبلغ</th>
                                                <th>نحوه پرداخت</th>
                                                <th>وضعیت نهایی</th>
                                            </tr>
                                            </thead>
                                            <tbody class="table-border-bottom-0">
                                            @foreach($payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->date }}</td>
                                                    <td>
                                                        <a href="{{ route('dashboard.online-order.show',$payment->order->id) }}"
                                                        style="border-bottom: 1px dashed #fff"
                                                        >{{ $payment->order->invoice_number }}</a>
                                                    </td>
                                                    <td dir="rtl">{{ $payment->payment_number }}</td>
                                                    <td>{{ $payment->reference_id ?? '-' }}</td>
                                                    <td>{{ number_format($payment->amount) }} تومان </td>
                                                    <td>{{ $payment->payMode->display_name }}</td>
                                                    <td>
                                                       @if($payment->amount == $payment->paid_amount)
                                                           <span class="badge bg-label-success">موفقیت‌آمیز</span>
                                                        @else
                                                           <span class="badge bg-label-warning">پرداخت ناموفق</span>
                                                       @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-center mt-2">
                                    {{ $payments->links() }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
