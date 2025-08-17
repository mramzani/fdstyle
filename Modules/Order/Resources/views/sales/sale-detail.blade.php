<div class="offcanvas-header">
    <div>
        <h5 id="offcanvasEndLabel" class="offcanvas-title">
            اطلاعات فاکتور: {{ $sale->invoice_number }}
            @php
                $percent = round(($sale->paid_amount * 100) / $sale->total);
            @endphp
        </h5>
            <div class="d-inline-block" style="width: 80%">
                <div class="progress my-2" style="height: 12px;">
                    <div class="progress-bar {{ $percent==100 ? 'bg-success' : '' }}" role="progressbar"
                         style="width: {{$percent}}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>

            <small class="d-inline-block">{{ $percent != 100 ? $percent . '%' : '' }} {!! $percent == 100 ? '<i class="bx bxs-check-circle" style="color:#09e444"></i>' : '' !!}</small>
    </div>
    <div class="d-flex justify-content-between">
        @can('order_payments_create')
            @if($sale->total > $sale->paid_amount)
                <div class="card-header-elements ms-auto">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPayment">
                        <span class="tf-icon bx bx-plus bx-xs"></span> افزودن پرداخت
                    </button>
                </div>
            @endif
        @endcan

        <button type="button" class="btn-close ms-2 text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
</div>
<div class="offcanvas-body mx-0 flex-grow-0">
    <div class="table-responsive text-nowrap">
        <table class="table table-borderless">
            <tbody>
            <tr class="table-dark">
                <th>تاریخ‌سفارش</th>
                <th>شماره فاکتور</th>
                <th>مشتری</th>
            </tr>
            <tr>
                <td>{{ $sale->date_time }}</td>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->customer->full_name }}</td>
            </tr>

            <tr class="table-dark">
                <th>وضعیت سفارش</th>
                <th>وضعیت پرداخت</th>
                <th>ایجاد توسط:</th>
            </tr>
            <tr>
                <td>{!! $sale->badge_order_status !!}</td>
                <td>{!! $sale->badge_payment_status !!}</td>
                <td>{{ $sale->staff->full_name }}</td>
            </tr>

            <tr class="table-dark">
                <th>کل مبلغ فاکتور</th>
                <th>پرداخت شده</th>
                <th>مانده فاکتور</th>
            </tr>
            <tr>
                <td>{{ number_format($sale->total) }} تومان</td>
                <td>{{ number_format($sale->paid_amount) }} تومان</td>
                <td>{{ number_format($sale->due_amount) }} تومان</td>
            </tr>

            <tr class="table-dark">
                <th>تخفیف</th>
                <th>حمل‌و‌نقل</th>
                <th>مالیات</th>
            </tr>
            <tr>
                <td>{{ number_format($sale->discount) }} تومان</td>
                <td>{{ number_format($sale->shipping) }} تومان</td>
                <td>0</td>
            </tr>

            </tbody>
        </table>
    </div>

    <div class="card shadow-none bg-transparent">
        <ul class="nav nav-tabs tabs-line" role="tablist">
            @can('order_payments_view')
                <li class="nav-item">
                    <button type="button"
                            class="nav-link active"
                            role="tab" data-bs-toggle="tab"
                            data-bs-target="#payment-list"
                            aria-controls="payment-list" aria-selected="true">
                        پرداخت‌ها
                    </button>
                </li>
            @endcan

            <li class="nav-item">
                <button type="button"
                        class="nav-link @cannot('order_payments_view') active @endcannot"
                        role="tab" data-bs-toggle="tab"
                        data-bs-target="#product-list"
                        aria-controls="product-list"
                        aria-selected="false">
                    محصولات
                </button>
            </li>
        </ul>
        <div class="tab-content">
            @can('order_payments_view')
                <div class="tab-pane fade show active" id="payment-list"
                     role="tabpanel">

                    @if($sale->payments()->count() == 0)
                        <div class="alert alert-warning">چیزی پیدا نشد!</div>
                    @else
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                <tr>
                                    <th>تاریخ پرداخت</th>
                                    <th>مبلغ</th>
                                    <th>روش پرداخت</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                @foreach($sale->payments()->where('payment_type','in')->get() as $payment)
                                    <tr>
                                        <td>{{ verta($payment->date)->formatDate()  }}</td>
                                        <td>{{ number_format($payment->paid_amount) }}</td>
                                        <td>{{ $payment->payMode->display_name }}</td>
                                        <td>
                                            @can('order_payments_delete')
                                                <div class="d-inline-block text-nowrap">
                                                    <a href="#" class="btn btn-sm btn-icon d-none"><i class="bx bx-edit"></i></a>
                                                    <form action="{{ route('dashboard.payment.destroy',$payment) }}"
                                                          id="deletePaymentConfirm-{{ $payment->id }}" method="post"
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

                </div>
            @endcan

            <div class="tab-pane fade @cannot('order_payments_view') show active @endcannot" id="product-list"
                 role="tabpanel">
                @if($sale->products()->count() == 0)
                    <div class="alert alert-warning">چیزی پیدا نشد!</div>
                @else
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover table-responsive">
                            <thead class="table-dark">
                            <tr>
                                <th>نام محصول</th>
                                <th>تعداد</th>
                                <th>قیمت واحد</th>
                                <th>تخفیف</th>
                                <th>جمع</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            @foreach($sale->products as $item)
                                <tr>
                                    <td>{{ $item->pivot->product->name }} {{ $item->pivot->variant != null ? ' - ' . $item->pivot->variant->option->valuable->title : '' }}</td>
                                    <td>{{ $item->pivot->quantity }}</td>
                                    <td>{{ number_format($item->pivot->unit_price) }}</td>
                                    <td>{{ number_format($item->pivot->total_discount) }}</td>
                                    <td>{{ number_format($item->pivot->subtotal) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @once
        <script>
            $(".delete-payment").on('click', function (event) {
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
                    buttonsStyling: false,
                }).then(function (value) {
                    if (!value.dismiss) {
                        $("#deletePaymentConfirm-" + id).submit();
                    }
                });
            });
        </script>
    @endonce
</div>
