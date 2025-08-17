<div class="card shadow-none bg-transparent">
    <div class="card-body">
        <!-- Search Box -->
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <label for="customer" class="form-label">نام مشتری</label>
                <input type="text" id="customer" wire:model.debounce.500="filters.customer" class="form-control">
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label for="invoice_number" class="form-label">شماره تراکنش</label>
                <input type="text" id="invoice_number" wire:model.debounce.500="filters.payment_number" class="form-control">
            </div>

        </div>
        <!-- Search Box -->
        <div class="row">
            @if($payments->count() == 0)
                <div class="alert alert-warning">چیزی پیدا نشد!</div>
            @else
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>تاریخ پرداخت</th>
                            <th>شماره پرداخت</th>
                            <th>مشتری</th>
                            <th>مبلغ</th>
                            <th>نحوه پرداخت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach($payments as $payment)

                            <tr>
                                <td>{{ $payment->date }}</td>
                                <td dir="rtl">{{ $payment->payment_number }}</td>
                                <td>
                                    @if($paymentType == 'in')
                                        <a href="{{ route('dashboard.customers.show',$payment->customer->id) }}">{{ $payment->customer->full_name }}</a>
                                    @elseif($paymentType == 'out')
                                        <a href="{{ route('dashboard.suppliers.show',$payment->supplier->id) }}">{{ $payment->supplier->full_name }}</a>
                                    @endif
                                </td>
                                <td>{{ number_format($payment->amount) }}</td>
                                <td>{{ $payment->payMode->display_name }}</td>
                                <td>
                                    @can('order_payments_delete')
                                    <div class="d-inline-block text-nowrap">
                                        <a href="#" class="btn btn-sm btn-icon d-none"><i class="bx bx-edit"></i></a>
                                        <form action="{{ route('dashboard.payment.destroy',$payment) }}" id="deletePaymentConfirm-{{ $payment->id }}" method="post"
                                              class="btn-group">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-icon
                                                    delete-payment"
                                                    data-id="{{ $payment->id }}"><i
                                                    class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
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
