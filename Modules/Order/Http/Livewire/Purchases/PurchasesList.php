<?php

namespace Modules\Order\Http\Livewire\Purchases;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Order\Entities\Order;

class PurchasesList extends Component
{
    use WithPagination;
    public $paymentStatus = null;
    public string $active = 'all';
    public $page = 1;
    protected $queryString = ['page' => ['except' => 1]];
    protected $paginationTheme = 'bootstrap';

    public array $filters = [
        'supplier' => '',
        'invoice_number' => '',
    ];

    public function render()
    {
        $query = Order::query()
            ->where('order_type', 'purchases')
            ->when($this->filters['invoice_number'], fn($query, $reference) =>
            $query->where('invoice_number', 'LIKE', '%' . $reference . '%'))
            ->when($this->filters['supplier'], fn($query, $supplier) =>
            $query->whereHas('supplier', function ($relation) use ($supplier) {
                $relation->where(\DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', '%' . $supplier . '%');
            }));

        if ($this->paymentStatus != null) {
            $query->where('payment_status', $this->paymentStatus);
        }

        $purchases = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('order::livewire.purchases.purchases-list',[
            'purchases' => $purchases
        ]);
    }

    public function updatedFilters()
    {
        $this->resetPage();
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
