<?php

namespace Modules\Order\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Product\Entities\Product;
use Modules\Product\Traits\SearchResult;

class PosResult extends Component
{
    use SearchResult;
    protected $listeners = ['productAdded'];
    public $products;

    public function mount()
    {
        $this->products = Collection::empty();
    }

    public function render()
    {
        return view('order::livewire.pos-result');
    }

    public function productAdded(array $index)
    {
        $product = $this->getFirstProductByCode($index['code']);

        if (!$product) {
            $product = Product::ProductJoinVariant($index['code']);
        }

        $newProduct = $this->generateProductArray($product,$index['code']);

        $this->checkDuplicatedProduct($product,$index,$newProduct);

        $this->dispatchBrowserEvent('calculateSubTotal', []);
        $this->dispatchBrowserEvent('productAddedToTable', []);
    }




    /**
     * OverRide Generate Product Array
     * @param $product
     * @param $code
     * @return array
     */
    public function generateProductArray($product, $code): array
    {
        $this->product = $product;
        if ($product->existsVariantByCode($code)) {
            $variant = $this->getProductVariantByCode($code);
            $this->newProduct['code'] = $product->code ?? $product->barcode;
            $this->newProduct['variant_id'] = $product->product_variant_id;
            $this->newProduct['name'] = $product->name . ' - ' . $variant->option->valuable->title;
            $this->newProduct['purchase_price'] = $variant->purchase_price;
            $this->newProduct['sales_price'] = $variant->sales_price;
            $this->newProduct['quantity'] = $variant->quantity;
        }
        else {
            $this->newProduct['code'] = $product->barcode;
            $this->newProduct['name'] = $product->name;
            $this->newProduct['purchase_price'] = $product->detail->purchase_price;
            $this->newProduct['sales_price'] = $product->detail->sales_price;
            $this->newProduct['quantity'] = $product->detail->current_stock;
        }
        $this->newProduct['id'] = $product->id;

        return $this->newProduct;
    }

    public function deleteRow($key)
    {
        $this->products->forget($key);

        $this->dispatchBrowserEvent('calculateSubTotal', []);
    }


}
