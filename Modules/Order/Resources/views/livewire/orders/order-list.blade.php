<div class="card shadow-none bg-transparent">
    <div class="card-body">
        <!-- Search Box -->
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <label for="customer" class="form-label">نام مشتری</label>
                <input type="text" id="customer" wire:model.debounce.500="filters.customer" class="form-control">
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
                @if(count($orders) == 0)
                    <div class="alert alert-warning">چیزی پیدا نشد!</div>
                @else
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>شماره فاکتور</th>
                                <th>تاریخ فروش</th>
                                <th>مشتری</th>
                                <th>کل مبلغ</th>
                                <th>مبلغ پرداختی</th>
                                <th>مانده فاکتور</th>
                                <th>وضعیت پرداخت</th>
                                <th>وضعیت سفارش</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            @foreach($orders as $order)
                                <tr data-max-amount="{{ $order->due_amount }}">
                                    <td>{{ $order->invoice_number }}</td>
                                    <td dir="rtl">{{ $order->date_time }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.customers.show',$order->customer->id) }}">{{ $order->customer->full_name }}</a>
                                    </td>
                                    <td>{{ number_format($order->total) }}</td>
                                    <td>{{ number_format($order->paid_amount) }}</td>
                                    <td>{{ number_format($order->due_amount) }}</td>
                                    <td>{!! $order->badge_payment_status !!}</td>
                                    <td>{!! $order->order_status_for_panel !!}</td>

                                    <td>
                                        <div class="d-inline-block text-nowrap">
                                            @can('order_details_view')
                                                <a href="{{ route('dashboard.online-order.show',$order->id) }}"
                                                   class="btn btn-sm btn-icon"><i class="bx bx-show"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="d-flex justify-content-center mt-2">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
