<?php

namespace Modules\Order\Http\Livewire\Sales;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Order\Entities\Order;

class SalesList extends Component
{
    use WithPagination;

    public $paymentStatus = null;
    public string $active = 'all';
    public $page = 1;
    protected $queryString = ['page' => ['except' => 1]];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshSaleList' => '$refresh'];

    public array $filters = [
        'customer' => '',
        'invoice_number' => '',
    ];

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::query()
            ->where('order_type', 'sales')
            ->when($this->filters['invoice_number'], fn($query, $reference) =>
            $query->where('invoice_number', 'LIKE', '%' . $reference . '%'))
            ->when($this->filters['customer'], fn($query, $customer) =>
            $query->whereHas('customer', function ($relation) use ($customer) {
                $relation->where(\DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', '%' . $customer . '%');
            }));


        if ($this->paymentStatus != null) {
            $query->where('payment_status', $this->paymentStatus);
        }
        $sales = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('order::livewire.sales.sales-list', ['sales' => $sales]);
    }

    public function getBy($field)
    {
        if ($field == 'paid') {
            $this->paymentStatus = $field;
            $this->active = $field;
        } elseif ($field == 'unpaid') {
            $this->paymentStatus = $field;
            $this->active = $field;
        } else {
            $this->paymentStatus = null;
            $this->active = 'all';
        }

    }

}
