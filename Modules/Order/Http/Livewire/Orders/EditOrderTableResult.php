<?php

namespace Modules\Order\Http\Livewire\Orders;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\Product\Traits\SearchResult;

class EditOrderTableResult extends Component
{
    use SearchResult;

    public $listeners = ['productAdded'];
    public Order $sale;
    public Collection $products;

    public function mount($sale)
    {
        $this->sale = $sale;
        $this->products = Collection::make($sale->products);
    }

    public function render()
    {
      //  dump($this->products->values()->all());
        return view('order::livewire.orders.edit-order-table-result');
    }


    public function productAdded(array $index)
    {
        $product = $this->getFirstProductByCode($index['code']);

        if (!$product) {
            $product = Product::ProductJoinVariant($index['code']);
        }


        $newProduct = $this->generateProductArray($product, $index['code']);


        $this->checkDuplicatedProduct($product, $index, $newProduct);

        $this->dispatchBrowserEvent('productAddedToTable', []);
        $this->dispatchBrowserEvent('calculateSubTotal', []);
        //dd($this->products);

    }

    public function deleteRow($key)
    {
        $this->products->offsetUnset($key);

        $this->dispatchBrowserEvent('calculateSubTotal', []);
    }


    public function generateProductArray($product, $code): array
    {
        $this->product = $product;

        if ($this->product->existsVariantByCode($code)){
            $variant = $this->getProductVariantByCode($code);
            $this->newProduct['variant_id'] = $this->product->product_variant_id;
            $this->newProduct['name'] = $this->product->name . ' - ' . $variant->option->valuable->title;
            $this->newProduct['unit_price'] = $this->product->sales_price;
            $this->newProduct['code'] = $this->product->code;
            $this->newProduct['current_stock'] = $this->product->quantity;
        } else {
            $this->newProduct['code'] = $this->product->code;
            $this->newProduct['name'] = $this->product->name;
            $this->newProduct['current_stock'] = $this->product->detail->current_stock;
            $this->newProduct['unit_price'] = $this->product->detail->sales_price;
        }

        $this->newProduct['id'] = $this->product->id;
        $this->newProduct['quantity'] = 1;

        return $this->newProduct;
    }

}
