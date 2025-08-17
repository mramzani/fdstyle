<?php

namespace Modules\Front\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $products = Product::select([
            'id', 'id as product_id', 'name as page_url'
        ])->orderBy('id', 'DESC')->paginate(100);

        $productsUpdated = $products->getCollection()
            ->map(function (Product $product) use ($products) {
                $product->old_price = (int) $product->detail->sales_price;
                $product->price = (int) $product->detail->isActivePromotion()
                    ? (int) $product->detail->promotion_price
                    : (int) $product->detail->sales_price;
                $product->availability = $product->hasStockWithVariant() ? 'instock' : '';
                return collect($product->toArray())
                    ->only(['product_id', 'page_url', 'old_price','price','availability'])
                    ->all();
            });
        $products->setCollection($productsUpdated);


        return response()->json([
            'data' => $products,
        ]);

    }
    
    public function vibe(Request $request)
    {
        $pageNumber = $request->query('page_number', 1);
        $size = $request->query('size', 15);

        $products = Product::with('categories')
            ->select(['id', 'name'])
            ->orderBy('created_at', 'DESC')
            ->paginate($size, ['*'], 'page', $pageNumber);


        $data = $products->map(function ($product) {
            return [
                'product-id' => $product->id,
                'product-name' => $product->name,
                'product-available' => (bool)$product->hasStockWithVariant(),
            ];
        });
        return response()->json($data);
    }

    public function vibeProduct(Product $product)
    {
        $product->load(['categories', 'brand', 'detail', 'ProductVariant.option', 'attributes.values']);

        $data = [
            'product-id' => $product->id,
            'product-name' => $product->name,
            'product-category' => $product->categories->pluck('title_fa')->first() ?? 'بدون دسته‌بندی',
            'product-url' => $product->page_url,
            'product-images' => $product->image == null
                ? [asset('assets/panel/img/no-image-available.jpg')]
                : array_map(function ($image) use ($product) {
                    return $product->getImageUrl(trim($image));
                }, explode(',', $product->image)),
            'price-main' => (int) $product->detail->sales_price ?? 0,
            'price-sale' => (int) $product->detail->isActivePromotion() ? (int) $product->detail->promotion_price : (int) $product->detail->sales_price,
            'product-description' => $product->description ?? '',
            'brand' => $product->brand->title ?? 'بدون برند',
            'properties' => $product->attributes->map(function ($attribute) {
                return [
                    'name' => $attribute->name,
                    'value' => $attribute->pivot->value_id ? $attribute->values->find($attribute->pivot->value_id)->value : '',
                ];
            })->toArray(),
            'product-variant' => $product->ProductVariant->map(function ($variant) use ($product) {
                return [
                    'variant-title' => $product->name . ' سایز ' . $variant->option->valuable->title,
                    'price-main' => $variant->purchase_price ?? 0,
                    'price-sale' => $variant->sales_price ?? 0,
                    'variant-available' => $product->hasStockWithVariant() ? true : false,
                    'properties' => [
                        [
                            'name' => $variant->variant->type,
                            'value' => $variant->option->valuable->title,
                        ]
                    ],
                ];
            })->toArray(),
        ];

        return response()->json($data);
    }

}
