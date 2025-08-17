<div class="card shadow-none bg-transparent">
    <div class="card-body">
        <!-- Search Box -->
        <div class="row">
            <div class="col-12 col-md-4 mb-3">
                <label for="customer" class="form-label">نام تامین‌کننده</label>
                <input type="text" id="customer" wire:model.debounce.500="filters.supplier" class="form-control">
            </div>
            <div class="col-12 col-md-4 mb-3">
                <label for="invoice_number" class="form-label">شماره فاکتور</label>
                <input type="text" id="invoice_number" wire:model.debounce.500="filters.invoice_number"
                       class="form-control">
            </div>

        </div>
        <!-- Search Box -->
        <ul class="nav nav-tabs tabs-line" role="tablist">
            <li class="nav-item">
                <button type="button" wire:click="getBy('all')" class="nav-link {{ $active == 'all' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-tabs-line-card-active"
                        aria-controls="navs-tabs-line-card-active" aria-selected="true">
                    همه‌ی فروش‌ها
                </button>
            </li>
            <li class="nav-item">
                <button type="button" wire:click="getBy('paid')"
                        class="nav-link {{ $active == 'paid' ? 'active' : '' }}"
                        role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-tabs-line-card-active" aria-controls="navs-tabs-line-card-link"
                        aria-selected="false">
                    پرداخت شده
                </button>
            </li>
            <li class="nav-item">
                <button type="button" wire:click="getBy('unpaid')"
                        class="nav-link {{ $active == 'unpaid' ? 'active' : '' }}" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-tabs-line-card-active" aria-controls="navs-tabs-line-card-link"
                        aria-selected="false">
                    پرداخت نشده
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="navs-tabs-line-card-active" role="tabpanel">
                @if($purchases->count() <= 0)
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
                            @foreach($purchases as $purchase)
                                <tr data-max-amount="{{ $purchase->due_amount }}">
                                    <td>{{ $purchase->invoice_number }}</td>
                                    <td dir="rtl">{{ $purchase->date_time }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.suppliers.show',$purchase->supplier->id) }}">{{ $purchase->supplier->full_name }}</a>
                                    </td>
                                    <td>{{ number_format($purchase->total) }}</td>
                                    <td>{{ number_format($purchase->paid_amount) }}</td>
                                    <td>{{ number_format($purchase->due_amount) }}</td>
                                    <td>{!! $purchase->badge_payment_status !!}</td>
                                    <td>{!! $purchase->badge_order_status !!}</td>
                                    <td>
                                        <div class="d-inline-block text-nowrap">
                                            @can('purchases_edit')
                                                <a href="{{ route('dashboard.purchase.edit',$purchase->id) }}"
                                                   class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>
                                            @endcan
                                            @can('purchase_show_detail')
                                                    <button href="#" type="button" data-bs-toggle="offcanvas"
                                                            onclick="showDetail(this,{{ $purchase->id }})"
                                                            id="show-detail-{{ $purchase->id }}"
                                                            data-bs-target="#offcanvasShowSaleDetails"
                                                            aria-controls="offcanvasShowSaleDetails"
                                                            class="btn btn-sm btn-icon">
                                                        <i class="bx bx-show"></i>
                                                    </button>
                                            @endcan

                                                @can('purchases_delete')
                                                    @if(!$purchase->payments()->exists())
                                                        <form action="{{ route('dashboard.purchase.destroy',$purchase->id) }}"
                                                              id="deletePurchaseConfirm-{{ $purchase->id }}" method="post"
                                                              class="btn-group">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-icon
                                                    delete-purchase"
                                                                    data-id="{{ $purchase->id }}"><i
                                                                    class="bx bx-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
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
                    {{ $purchases->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
