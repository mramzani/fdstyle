<?php

namespace Modules\Front\Http\Controllers;

use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Helper\Setting\ProductSettings;
use Modules\PageBuilder\Entities\Home;
use Modules\Product\Entities\Product;

class HomeController extends BaseController
{

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->seo()
            ->setTitle(company()->site_title)
            ->setDescription(get_seo_description())
            ->setCanonical(route('front.home'));

        OpenGraph::setDescription(get_seo_description());
        OpenGraph::setTitle(company()->site_title);
        OpenGraph::setUrl(route('front.home'));
        OpenGraph::addProperty('type', 'webpage');

        $home = Home::whichHomeToDisplay();

        return view('front::index',compact('home'));
    }

    public function search(Request $request)
    {

        $this->seo()
            ->setTitle('نتایج جستجو برای '. $request->input('terms'))
            ->setCanonical(route('front.product.search',['terms' => $request->input('terms')]));
        $request->validate([
            'terms' => 'required|min:3'
        ]);
        $searchTerms = explode(' ',$request->input('terms'));
        $query = Product::query();
        foreach ($searchTerms as $searchTerm){
            $query->where(function ($q) use ($searchTerm){
                $q->where('name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $display_product_without_image = app(ProductSettings::class)->display_product_without_image;

        $query->withImage($display_product_without_image);

        $query->orderBy('created_at','desc');
        $products = $query->paginate(20);
        $products->setCollection($products->sortByDesc('detail.promotion_end_date')->sortByDesc('image')->sortBy('detail.status'));

        return view('front::search.result',compact('products'));
    }

}
