<?php

namespace Modules\Product\Traits;

use Illuminate\Support\Collection;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;

trait SearchResult
{
    public array $newProduct = [];
    public Product $product;

    public function generateProductArray($product,$code): array
    {
        $this->product = $product;

        if ($this->product->existsVariantByCode($code)) {
            $variant = $this->getProductVariantByCode($code);
            $this->newProduct['code'] = $this->product->code;
            $this->newProduct['variant_id'] = $this->product->product_variant_id;
            $this->newProduct['name'] = $this->product->name . ' - ' . $variant->option->valuable->title;
        } else {
            $this->newProduct['code'] = $this->product->barcode;
            $this->newProduct['name'] = $this->product->name;
        }
        $this->newProduct['id'] = $this->product->id;

        return $this->newProduct;
    }

    private function getProductVariantByCode($code): ?ProductVariant
    {
        return $this->product->getProductVariant()->where('code', $code)->first();
    }

    protected function getFirstProductByCode($code): ?Product
    {
        return Product::where('barcode', $code)->first();
    }

    protected function checkDuplicatedProduct($product,$index,$newProduct)
    {
        if ($product->notAllowedAddProduct($index['code'])) {
            $this->addError('not_allowed_add_product_to_factor', 'محصولی که دارای تنوع می‌باشد قابل افزودن نیست');
        } else {
            $this->products->contains('code', $index['code'])
                ? $this->addError('duplicate_product', 'این محصول در لیست موجود میباشد.')   // if product duplicate add quantity
                : $this->products->add($newProduct);
        }
    }
}
