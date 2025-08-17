<?php

namespace Modules\Product\Http\Livewire\Adjustments;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Product\Entities\Product;
use Modules\Product\Traits\SearchResult;

class ProductSearchResult extends Component
{
    use SearchResult;
    public $listeners = ['productAdded'];
    public Collection $products;

    public function mount()
    {
        $this->products = Collection::empty();
    }

    public function render()
    {
        return view('product::livewire.adjustments.product-search-result');
    }

    public function productAdded(array $index)
    {
        $product = $this->getFirstProductByCode($index['code']);

        if (!$product) {
            $product = Product::ProductJoinVariant($index['code']);
        }

        $newProduct = $this->generateProductArray($product,$index['code']);

        $this->checkDuplicatedProduct($product,$index,$newProduct);

    }

    public function deleteRow($key)
    {
        $this->products->forget($key);
    }




}
