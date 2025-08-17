@if(count($orders) > 0)
    <section class="table--order shadow-around mt-4">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>کد سفارش</th>
                    <th>تاریخ</th>
                    <th>مبلغ</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="order-code">{{ $order['invoice_number'] }}</td>
                        <td>{{ $order['order_date'] }}</td>
                        <td>{{ $order['total'] }} تومان</td>
                        <td>{!! $order['order_status'] !!}</td>
                        <td class="order-detail-link">
                            <a href="{{ route('front.user.orders.show',$order['id']) }}">
                                <i class="far fa-chevron-left"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
@else
    <div class="empty-box">
        <div class="icon">
            <i class="fal fa-times-circle"></i>
        </div>
        <div class="message">
            <p>سفارشی برای نمایش وجود نداره!</p>
        </div>
    </div>
@endif


