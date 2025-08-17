<div class="offcanvas-header">
    <h5 id="offcanvasStartLabel" class="offcanvas-title">لیست تنوع محصولات</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body mx-0 flex-grow-0">
    @if($variants->count() == 0)
        <div class="alert alert-warning">این محصول بدون تنوع می‌باشد!</div>
    @else
    <div class="card shadow-none bg-transparent">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>کدمحصول</th>
                        <th>قیمت خرید</th>
                        <th>قیمت فروش</th>
                        <th>موجودی</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($variants as $variant)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $variant->option->valuable->title }}</td>
                            <td dir="ltr">{{ $variant->code }}</td>
                            <td>{{ $variant->purchase_price }}</td>
                            <td>{{ $variant->sales_price }}</td>
                            <td><span class="badge bg-primary">{{ $variant->quantity }}</span>  عدد </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
    </div>
    @endif
</div>
