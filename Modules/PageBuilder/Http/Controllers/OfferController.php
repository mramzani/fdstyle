<?php

namespace Modules\PageBuilder\Http\Controllers;


use App\Services\Common;
use Illuminate\Http\Request;
use Modules\Category\Entities\Category;
use Modules\PageBuilder\Entities\Offers;
use Modules\PageBuilder\Http\Requests\Offer\StoreRequest;
use Modules\Product\Entities\Product;
use Mramzani\LaravelController\BaseController;

class OfferController extends BaseController
{
    protected $model = Offers::class;

   protected $storeRequest = StoreRequest::class;

    protected $module = "PageBuilder";

    protected ?int $paginate_number = 15;


    public function storing($offer)
    {
        $products = request()->input('products');
        $this->refreshOffer($products,$offer);
    }

    public function modifyRequest(\Illuminate\Support\Collection $collect): \Illuminate\Support\Collection
    {
        $collect->put('start_date', Common::convertDateTimeToGregorian($collect->get('start_date')));
        $collect->put('end_date', Common::convertDateTimeToGregorian($collect->get('end_date')));
        return $collect;
    }

    public function updating($offer)
    {
        $products = collect($offer->products()->select(['id'])->get())->toArray();
        /* $products = $offer->products->map(function ($product){
            return $product->toArray()
                ->only(['id'])
                ->toArray();
        })->flatten();*/
        $this->refreshOffer(\Arr::flatten($products),$offer);
    }
    public function products(Request $request)
    {
        $request->validate([
            'terms' => 'required|min:3',
        ]);

        $searchTerms = explode(' ', $request->input('terms'));
        $products = Product::query();
        foreach ($searchTerms as $searchTerm) {
            $products->available()->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('barcode', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $products = $products->get()->collect()
            ->map(function ($product){
                return collect($product->toArray())
                    ->only(['id','name', 'barcode'])
                    ->all();
            });

        return response()->json([
            'data' => $products->toArray(),
        ]);
    }

    public function landing(Offers $offer)
    {
        return view('pagebuilder::offer.show',compact('offer'));
    }

    private function refreshOffer(array $products,Offers $offer)
    {
        $promotion_start_date = Common::convertDateTimeToGregorian(request()->input('start_date'));
        $promotion_end_date = Common::convertDateTimeToGregorian(request()->input('end_date'));
        $percent = request()->input('percent');
        collect($products)->each(function ($product_id) use ($percent,$promotion_start_date,$promotion_end_date,$offer){
            $product = Product::findOrFail($product_id);
            //get sales_price => calculate promotion_price => $promotion_price
            $discount_amount = (int) round(($product->detail->sales_price * $percent) / 100);
            $promotion_price = $product->detail->sales_price - $discount_amount;
            //$promotion_price = (int) round($promotion_price / 1000) * 1000;
            //update from detail => 1.promotion_price 2.promotion_start_date 3.promotion_end_date
            $product->detail()->update([
                'promotion_price' => $promotion_price,
                'promotion_start_date' => $promotion_start_date,
                'promotion_end_date' => $promotion_end_date
            ]);
            //update offer_id from products => $offer->id
            $product->update([
                'offer_id' => $offer->id
            ]);
        });

    }
    
    protected function deleting(Offers $object)
    {
        foreach ($object->products()->get() as $product){
            $product->update([
                'offer_id' => null,
            ]);
            $product->detail()->update([
                'promotion_price' => null,
                'promotion_start_date' => null,
                'promotion_end_date' => null,
            ]);
        }
    }
}
