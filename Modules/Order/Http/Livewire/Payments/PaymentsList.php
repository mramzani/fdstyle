<?php

namespace Modules\Order\Http\Livewire\Payments;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Order\Entities\Payment;

class PaymentsList extends Component
{
    use WithPagination;

    public $paymentType;
    public $page = 1;
    protected $queryString = ['page' => ['except' => 1]];
    protected $paginationTheme = 'bootstrap';
    public array $filters = [
        'customer' => '',
        'payment_number' => '',
    ];

    public function mount($type = 'in')
    {
        $this->paymentType = $type;
    }

    public function render()
    {
        $rel = $this->paymentType == 'in' ? 'customer' : 'supplier' ;
        $query = Payment::query()->with(['customer','supplier'])
            ->where('warehouse_id', warehouse()->id)
            ->where('payment_type', $this->paymentType)
            ->when($this->filters['payment_number'], fn($query, $reference) => $query->where('payment_number', 'LIKE', '%' . $reference . '%'))
            ->when($this->filters['customer'], fn($query, $customer) => $query->whereHas($rel, function ($relation) use ($customer) {
                    $relation->where(\DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', '%' . $customer . '%');
             }));

        $payments = $query->orderBy('date', 'desc')->paginate(15);

        return view('order::livewire.payments.payments-list',['payments' => $payments]);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }
}
