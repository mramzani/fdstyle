<?php

namespace Modules\Order\Http\Livewire\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Order\Entities\Order;

class OrderList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public array $filters = [
        'customer' => '',
        'invoice_number' => '',
    ];

    public function render()
    {
        $query = Order::query()->where('order_type','online')
            ->when($this->filters['invoice_number'],fn($query,$reference) =>
                $query->where('invoice_number','LIKE','%' . $reference.'%'))
            ->when($this->filters['customer'], fn($query, $customer) =>
            $query->whereHas('customer', function ($relation) use ($customer) {
                $relation->where(\DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', '%' . $customer . '%');
            }));

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('order::livewire.orders.order-list',['orders' => $orders]);
    }
}
