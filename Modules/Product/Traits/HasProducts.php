<?php

namespace Modules\Product\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Dashboard\Helper\Setting\ProductSettings;
use Modules\Product\Entities\Product;

trait HasProducts
{
    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /*public function childProduct($id)
    {
        $product_count = app(ProductSettings::class)->carousel_pagination_number;
        $display_product_without_image = app(ProductSettings::class)->display_product_without_image;

        return Product::whereHas('category',function ($query) use ($id){
            return $query->where('id',$id)
                ->orWhere('parent_id',$id);
        })->available()->withImage($display_product_without_image)->take($product_count)->get();
    }*/


    public function parentProduct($id)
    {
        $product_count = app(ProductSettings::class)->carousel_pagination_number;
        $display_product_without_image = app(ProductSettings::class)->display_product_without_image;

        /*return Product::query()
            ->whereHas('categories',function (Builder $category) use ($id){
                    return $category->where('id',$id)
                                    ->orWhere('parent_id',$id);
            })->with('detail')
            ->available()
            ->withImage($display_product_without_image)
            ->orderBy('created_at','desc')
            ->take($product_count)
            ->get()->sortByDesc('detail.promotion_end_date');*/

        $query = Product::query()
            ->whereHas('categories', function (Builder $category) use ($id) {
                return $category->where('id', $id)
                    ->orWhere('parent_id', $id);
            })->with('detail')
            ->available()
            ->withImage($display_product_without_image);
        $query->join('product_details', 'product_details.product_id', '=', 'products.id');

        return $query
            ->orderBy('product_details.status', 'desc')
            ->orderBy('product_details.promotion_end_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->select('products.*')
            ->take($product_count)->get();
    }

    /**
     * @param $product
     * @return bool
     */
    public function hasProduct($product): bool
    {
        if (is_numeric($product)) {
            return $this->products()->where('id', $product)->exists();
        } elseif (is_string($product)) {
            return $this->products()->where('name', $product)->exists();
        }
        return false;
    }

    /**
     * @param string $sku
     * @return bool
     */
    public function hasProductBySku(string $sku): bool
    {
        return $this->products()->whereHas('skus', function ($q) use ($sku) {
            $q->where('code', $sku);
        })->exists();
    }
}
