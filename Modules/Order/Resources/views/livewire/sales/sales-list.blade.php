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
                @if(count($sales) == 0)
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
                            @foreach($sales as $sale)
                                <tr data-max-amount="{{ $sale->due_amount }}">
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td dir="rtl">{{ $sale->date_time }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.customers.show',$sale->customer->id) }}">{{ $sale->customer->full_name }}</a>
                                    </td>
                                    <td>{{ number_format($sale->total) }}</td>
                                    <td>{{ number_format($sale->paid_amount) }}</td>
                                    <td>{{ number_format($sale->due_amount) }}</td>
                                    <td>{!! $sale->badge_payment_status !!}</td>
                                    <td>{!! $sale->badge_order_status !!}</td>
                                    <td>
                                        <div class="d-inline-block text-nowrap">
                                            @can('sales_edit')
                                                <a href="{{ route('dashboard.sales.edit',$sale->id) }}"
                                                   class="btn btn-sm btn-icon"><i class="bx bx-edit"></i></a>
                                            @endcan

                                            @can('sales_show_detail')
                                                <button href="#" type="button" data-bs-toggle="offcanvas"
                                                        onclick="showDetail(this,{{ $sale->id }})"
                                                        id="show-detail-{{ $sale->id }}"
                                                        data-bs-target="#offcanvasShowSaleDetails"
                                                        aria-controls="offcanvasShowSaleDetails"
                                                        class="btn btn-sm btn-icon"><i class="bx bx-show"></i>
                                                </button>
                                            @endcan

                                            @can('sales_delete')
                                                @if(!$sale->payments()->exists())
                                                    <form action="{{ route('dashboard.sales.destroy',$sale) }}"
                                                          id="deleteSaleConfirm-{{ $sale->id }}" method="post"
                                                          class="btn-group">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-icon
                                                    delete-sale"
                                                                data-id="{{ $sale->id }}"><i
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
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
