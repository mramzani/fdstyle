<?php

namespace Modules\Product\Http\Livewire\Products;

use Illuminate\Support\Collection;
use Livewire\Component;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;

class ProductSearchInput extends Component
{
    public $selected = 0;
    public $keyword;
    public $open = false;
    public Collection $products;
    public bool $showAvailableProduct;


    public function mount(bool $showAvailableProduct = false)
    {
        $this->showAvailableProduct = $showAvailableProduct;
        $this->products = Collection::empty();
    }
    public function render()
    {
        return view('product::livewire.products.product-search-input');
    }

    #region search logic

    public function select($index)
    {
        if ($this->products->isNotEmpty()){
            $this->resetAll();
            $this->emit('productAdded',$this->products[$index]);
        }

    }

    public function updatedKeyword()
    {
        if ($this->keyword != ''){
            $this->open = true;
        } else {
            $this->open = false;
        }

        $this->products = Collection::empty();

        $searchTerms = explode(' ', $this->keyword);
        $query = Product::query();
        foreach ($searchTerms as $searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('barcode', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($this->showAvailableProduct){
            $query->available();
        }
        $products = $query->with(['detail', 'ProductVariant'])->orderBy('id', 'DESC')->get();

        collect($products)->map(function ($product) {
            $newProduct = $this->makeArrProduct($product);
            $this->products = Collection::make($this->products)->add($newProduct);

            if (!$product->getProductVariant()->isEmpty()) {
                $productVariants = $product->getProductVariant();

                if ($this->showAvailableProduct){
                    $productVariants = $product->getAvailableProductVariant();
                }
                foreach ($productVariants as $productVariant) {
                    $newProduct = $this->makeArrProduct($productVariant);
                    $this->products->add($newProduct);
                }
            }
        });
    }

    private function makeArrProduct($product): array
    {
        if ($product instanceof Product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->barcode,
                'image' => $product->image_url,
                'sales_price' => $product->detail->sales_price ?? 0,
                'purchase_price' => $product->detail->purchase_price ?? 0,
                'current_stock' => $product->detail->current_stock ?? 0,
            ];
        } elseif ($product instanceof ProductVariant) {
            return [
                'id' => $product->product->id,
                'name' => $product->product->name . ' - ' . $product->option->valuable->title,
                'code' => $product->code,
                'image' => $product->product->image_url,
                'sales_price' => $product->sales_price ?? 0,
                'purchase_price' => $product->purchase_price ?? 0,
                'current_stock' => $product->quantity ?? 0,
            ];
        } else {
            return [];
        }
    }

    private function resetAll()
    {
        $this->open = false;
        $this->keyword = '';
    }

    #endregion search
}
