<?php

namespace Modules\Front\Http\Controllers;

use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Front\Adapters\ProductAdapter;
use Modules\Product\Entities\Product;

class ProductController extends BaseController
{


    public function show(Product $product)
    {
        $this->seo()
            ->setTitle('خرید ' . $product->name)
            ->setDescription('خرید اینترنتی جدیدترین محصولات ' . $product->name)
            ->setCanonical(route('front.product.show', [$product->id,$product->slug]))
            ->addImages($product->image_url);

        OpenGraph::setDescription('خرید اینترنتی جدیدترین محصولات ' . $product->name);
        OpenGraph::setTitle('خرید ' . $product->name);
        OpenGraph::setUrl(route('front.product.show', $product->id));
        OpenGraph::addProperty('type', 'Product');

        JsonLdMulti::setTitle('خرید ' . $product->name);
        JsonLdMulti::setDescription('خرید اینترنتی جدیدترین محصولات ' . $product->name);

        JsonLdMulti::addImage($product->image_url);


        $categoriesBreadcrumb = Category::where('id', $product->category_id)
            ->with('parentsCategories')
            ->first();

        $productResource = new ProductAdapter($product);
        $data = (object)$productResource->transform();
        return view('front::product.show', compact('data', 'product', 'categoriesBreadcrumb'));
    }

}
