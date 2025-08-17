<?php

namespace Modules\Front\Http\Controllers;

use Artesaos\SEOTools\Facades\JsonLdMulti;

use Modules\Brand\Entities\Brand;
use Modules\Dashboard\Helper\Setting\ProductSettings;

class BrandController extends BaseController
{

    public function show(Brand $brand)
    {
        $title = $brand->seo_title != null ? $brand->seo_title : 'خرید محصولات ' . $brand->title_fa;
        $description = $brand->seo_description != null ? $brand->seo_description : 'خرید اینترنتی جدیدترین محصولات ' . $brand->title_fa;

        $this->seo()
            ->setTitle($title)
            ->setDescription($description)
            ->setCanonical(route('front.brand.list', $brand->slug));

        JsonLdMulti::setTitle($title);
        JsonLdMulti::setDescription($description);
        JsonLdMulti::setType('WebPage');
        $display_product_without_image = app(ProductSettings::class)->display_product_without_image;

        $query = $brand->products()->withImage($display_product_without_image);
        $query->join('product_details', 'product_details.product_id', '=', 'products.id');
        $products = $query
            ->orderBy('product_details.status', 'desc')
            ->orderBy('product_details.promotion_end_date','desc')
            ->orderBy('created_at', 'desc')
            ->select('products.*')
            ->paginate(20);

        return view('front::brand.index',compact('products','brand'));
    }

}
