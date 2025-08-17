<?php

namespace Modules\Front\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Entities\Brand;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SiteMapController extends BaseController
{
    public function product()
    {
       
        $site_map = Sitemap::create()
            ->add(Url::create(url('/'))
                ->setPriority(1.0)
                ->setLastModificationDate(Carbon::today())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );

        Product::orderBy('created_at','desc')->each(function (Product $product) use ($site_map) {
            $site_map->add(Url::create(route('front.product.show',[$product->id,$product->slug]))
                ->setPriority(1.0)
                ->setLastModificationDate(Carbon::today())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );
        });

        $site_map->writeToFile(base_path('../public_html/storage/product_sitemap.xml'));
    }

    public function category()
    {
        $site_map = Sitemap::create()
            ->add(Url::create(url('/'))
                ->setPriority(0.9)
                ->setLastModificationDate(Carbon::today())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );

        Category::each(function (Category $category) use ($site_map) {
            $site_map->add(Url::create(route('front.category.list',[$category->slug]))
                ->setPriority(0.9)
                ->setLastModificationDate(Carbon::today())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        $site_map->writeToFile(base_path('../public_html/storage/category_sitemap.xml'));
    }

    public function brand()
    {
        $site_map = Sitemap::create()
            ->add(Url::create(url('/'))
                ->setPriority(0.9)
                ->setLastModificationDate(Carbon::today())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );

        Brand::each(function (Brand $brand) use ($site_map) {
        $site_map->add(Url::create(route('front.brand.list',[$brand->slug]))
            ->setPriority(0.9)
            ->setLastModificationDate(Carbon::today())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        );
    });
        $site_map->writeToFile(base_path('../public_html/storage/brand_sitemap.xml'));
    }

    public function all()
    {
        $this->product();
        $this->category();
        $this->brand();

        $site_map = SitemapIndex::create()
            ->add('/storage/product_sitemap.xml')
            ->add('/storage/category_sitemap.xml')
            ->add('/storage/brand_sitemap.xml');

        $site_map->writeToFile(base_path('../public_html/storage/sitemap.xml'));
    }
}
