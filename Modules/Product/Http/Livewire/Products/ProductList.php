<?php

namespace Modules\Product\Http\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Product\Entities\Product;

class ProductList extends Component
{
    use WithPagination;
    public $keyword = '';
    public $page = 1;
    protected $queryString = ['page' => ['except' => 1]];
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $searchTerms = explode(' ',$this->keyword);
        $query = Product::query();
        foreach ($searchTerms as $searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('barcode', 'LIKE', '%' . $searchTerm . '%');
            });
        }


        $products = $query->with(['detail', 'brand', 'category'])
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('product::livewire.products.product-list',[
            'products' => $products
        ]);
    }

    public function updatedKeyword()
    {
        $this->resetPage();
    }
}
