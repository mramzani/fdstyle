<?php

namespace Modules\Front\Http\Controllers;

use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Category\Entities\Category;
use Modules\Dashboard\Helper\Setting\ProductSettings;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\VariantValue;

class CategoryController extends BaseController
{


    public function show(Category $category, Request $request)
    {
        $categoriesBreadcrumb = Category::where('id', $category->id)->with('parentsCategories')
            ->first();
        //set title and description
        $title = $category->seo_title != null ? $category->seo_title : 'خرید ' . $category->title_fa;
        $description = $category->seo_description != null ? $category->seo_description : 'خرید اینترنتی جدیدترین محصولات ' . $category->title_fa;
        $this->seo()
            ->setTitle($title)
            ->setDescription($description)
            ->setCanonical(route('front.category.list', $category->slug));

        //Schema Structure
        JsonLdMulti::setTitle($title);
        JsonLdMulti::setDescription($description);
        JsonLdMulti::setType('BreadcrumbList');
        JsonLdMulti::addValues([
            'itemListElement' => $this->getItems($categoriesBreadcrumb)
        ]);

        $display_product_without_image = app(ProductSettings::class)->display_product_without_image;
        $categories = $category->child->flattenTree()->pluck('id');
        $categories->push($category->id);

        $query = Product::whereHas('categories', function (Builder $query) use ($categories) {
            return $query->whereIn('id', $categories);
        });

        $query->when($request->query('colors'), function ($q, $color) {
            $variantIds = VariantValue::whereIn('valuable_id', $color)->pluck('id')->toArray();
            $q->whereHas('ProductVariant', function ($variant) use ($variantIds) {
                return $variant->whereIn('value_id', $variantIds);
            });
        });
        $query->when($request->query('sizes'), function ($q, $size) {
            $variantIds = VariantValue::whereIn('valuable_id', $size)->pluck('id')->toArray();
            $q->whereHas('ProductVariant', function ($variant) use ($variantIds) {
                return $variant->where('quantity', '>', 0)->whereIn('value_id', $variantIds);
            });
        });
        $query->when($request->query('attributes'), function ($product, $attributes) {
            collect($attributes)->each(function (array $value, int $key) use ($product) {
                $product->whereHas('attributes', function ($attribute) use ($value, $key) {
                    return $attribute->where('id', $key)
                        ->whereIn('value_id', $value);
                });
            });
        });

        $query->with('detail');

        $query->withImage($display_product_without_image);

        $query->join('product_details', 'product_details.product_id', '=', 'products.id');

        $products = $query
            ->orderBy('product_details.status', 'desc')
            ->orderBy('product_details.promotion_end_date','desc')
            ->orderBy('created_at', 'desc')
            ->select('products.*')
            ->paginate(20);

        return view('front::category.index', compact('products', 'categoriesBreadcrumb', 'category'));
    }

    private function getItems($categoriesBreadcrumb)
    {
        $items = [];
        if ($categoriesBreadcrumb) {
            if ($categoriesBreadcrumb->parentsCategories) $items = $this->getChildItem($categoriesBreadcrumb->parentsCategories);
            $items[] = [
                '@type' => 'ListItem',
                'name' => $categoriesBreadcrumb->title_fa,
                'item' => route('front.category.list', $categoriesBreadcrumb->slug),
            ];
        }
        foreach ($items as $index => $item) {
            $items[$index]['position'] = $index + 1;
        }
        return $items;
    }

    private function getChildItem($categoriesBreadcrumb)
    {
        $items = [];
        if ($categoriesBreadcrumb) {
            if ($categoriesBreadcrumb->parentsCategories) $items = $this->getChildItem($categoriesBreadcrumb->parentsCategories);
            $items[] = [
                '@type' => 'ListItem',
                'name' => $categoriesBreadcrumb->title_fa,
                'item' => route('front.category.list', $categoriesBreadcrumb->slug),
            ];
        }
        return $items;
    }


}
