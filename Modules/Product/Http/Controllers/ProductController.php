<?php

namespace Modules\Product\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Entities\StockHistory;
use Modules\Product\Exceptions\CantChangeCategoryException;
use Throwable;

class ProductController extends Controller
{
    private Request $request;
    public Collection $products;


    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->products = Collection::empty();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('product::product.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('product::product.create');
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     * @throws Throwable
     */
    public function store()
    {
        //validate
        $this->validateStore();

        \DB::beginTransaction();
        try {
            $fileName = (new Common($this->request))->multiUpload();
            //store
            $product = $this->productCreate($fileName);
            // store product detail with product
            $product->detail()->create([
                'warehouse_id' => company()->warehouse_id,
                'purchase_price' => $this->request->input('purchase_price'),
                'sales_price' => $this->request->input('sales_price'),
                'weight' => $this->request->input('weight'),
                'length' => $this->request->input('length'),
                'width' => $this->request->input('width'),
                'height' => $this->request->input('height'),
                'preparation_time' => $this->request->input('preparation_time'),
            ]);

            $product->categories()->sync($this->request->input('secondary_category'));

            \DB::commit();

        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            throw new \InvalidArgumentException('خطا در ایجاد محصول');
        }
        return \Response::json([
            'message' => trans('product::products.product created has successfully'),
            'data' => $product->id,
            'isSuccess' => true,
        ]);
    }

    /**
     * Show the specified resource.
     * @param Product $product
     * @return array
     */
    public function show(Product $product)
    {
        //return $product->category->value->valuable;
        //return ProductVariantAdapter::collection($product->getProductVariant());
        //return \Response::json($product->load('brand', 'category', 'detail','variations'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Product $product
     * @return Renderable
     */
    public function edit(Product $product)
    {

        $product->load('detail');
        return view('product::product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     * @param Product $product
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(Product $product)
    {
        $this->request->validate([
            'product_title' => 'string|required|max:120|unique:products,name,' . $product->id,
            'slug' => 'string|nullable|unique:products,slug,' . $product->id,
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'image' => 'array|nullable',
            'category_id' => 'numeric|nullable',
            'brand_id' => 'numeric|nullable',
            'unit_id' => 'numeric|nullable',
            'description' => 'string|nullable',
            'sales_price' => 'required|integer|gt:purchase_price',
            'purchase_price' => 'required|integer',
            'folder' => 'required',
            'guarantee' => 'required',
        ]);


        if (is_null($this->request->prev_img)) {
            $product->image = null;
        } else {
            $product->image = implode(",", array_unique($this->request->prev_img));
        }
        $product->save();

        $images = (new Common($this->request))->multiUpload();

        \DB::beginTransaction();
        try {
            if ($product->image != null && $images != null) {
                $product->image = $product->image . ',' . $images;
            } else if ($product->image == null) {
                $product->image = $images;
            }

            $oldVariantId = $product->category->variant_id;

            $product->update([
                'name' => $this->request->input('product_title'),
                'slug' => $this->request->input('slug'),
                'barcode' => $this->request->input('barcode'),
                'category_id' => $this->request->input('category_id'),
                'brand_id' => $this->request->input('brand_id'),
                'unit_id' => $this->request->input('unit_id'),
                'description' => $this->request->input('description'),
                'row' => $this->request->input('row'),
                'floor' => $this->request->input('floor'),
                'guarantee_id' => $this->request->input('guarantee'),
                'offer_id' => $this->request->input('offer_id'),
            ]);

            if (array_key_exists('category_id', $product->getChanges())) {
                $newCategory = Category::where('id', $product->getChanges()['category_id'])->firstOrFail();

                if ($oldVariantId != $newCategory->variant_id) {
                    throw new CantChangeCategoryException('به دلیل وجود تنوع محصول، امکان تغییر دسته‌بندی برای این محصول وجود ندارد.');
                }
            }
            $product->detail()->update([
                'purchase_price' => $this->request->input('purchase_price'),
                'sales_price' => $this->request->input('sales_price'),
                'weight' => $this->request->input('weight'),
                'length' => $this->request->input('length'),
                'width' => $this->request->input('width'),
                'height' => $this->request->input('height'),
                'preparation_time' => $this->request->input('preparation_time'),
                'promotion_price' => $this->request->input('promotion_price'),
                'promotion_start_date' => Common::convertDateTimeToGregorian($this->request->input('promotion_start_date')),
                'promotion_end_date' => Common::convertDateTimeToGregorian($this->request->input('promotion_end_date')),
            ]);
            $product->categories()->sync($this->request->input('secondary_category'));
            \DB::commit();

            return \Response::json([
                'isSuccess' => true,
                'data' => $product->id,
                'message' => trans('product::products.product updated has successfully'),
            ]);

        } catch (\Exception $exception) {
            \DB::rollBack();

            return \Response::json([
                'isFailed' => true,
                'message' => $exception->getMessage()
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Product $product)
    {
        \DB::beginTransaction();
        try {

            if ($product->adjustments()->exists() || $product->ProductVariant()->exists() || $product->orders()->exists())
                throw new \RuntimeException('این محصول قابل حذف نیست');

            if ($product->image != null) {
                $this->request->merge(['folder' => 'product']);
                (new Common($this->request))->deleteFile($product->image);
            }

            $product->delete();
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('error deleting product: ' . $exception->getMessage());
            return Common::redirectTo('products.index', 'alertWarning', 'error deleting product: ' . $exception->getMessage());
        }

        return Common::redirectTo('products.index', 'alertSuccess', __('product::products.product deleted successfully'));
    }

    /**
     * validate product store
     * @return void
     */
    private function validateStore(): void
    {
        $this->request->validate([
            'product_title' => 'string|required|max:120|unique:products,name',
            'slug' => 'string|nullable|unique:products,slug',
            'barcode' => 'required|unique:products,barcode',
            'image' => 'array|nullable',
            'category_id' => 'numeric|nullable',
            'brand_id' => 'numeric|nullable',
            'unit_id' => 'numeric|nullable',
            'description' => 'string|nullable',
            'purchase_price' => 'required|integer',
            'sales_price' => 'required|integer|gt:purchase_price',
            'folder' => 'required',
        ]);
    }

    /**
     * create product
     * @param $fileName
     * @return Product
     */
    private function productCreate($fileName): Product
    {
        return Product::create([
            'name' => $this->request->input('product_title'),
            'slug' => $this->request->input('slug'),
            'barcode' => $this->request->input('barcode'),
            'image' => $fileName,
            'category_id' => $this->request->input('category_id'),
            'brand_id' => $this->request->input('brand_id'),
            'guarantee_id' => get_default_guarantee() != null ? get_default_guarantee()->id : null,
            'unit_id' => unit(),
            'description' => $this->request->input('description'),
        ]);
    }

    public function variantList()
    {

        $variants = ProductVariant::where('product_id', $this->request->input('product_id'))
            ->get();

        return view('product::product.variant-list', compact('variants'));

    }

    public function stockHistories()
    {
        $histories = StockHistory::query()->where('product_id',$this->request->input('product_id'))->orderByDesc('created_at')->get();


        return view('product::product.stock-histories', compact('histories'));

    }

    public function generateBarcode()
    {
        return \Response::json([
            'barcode' => Common::NumericGenerator(config('product.barcode_length')),
            'success' => true
        ]);
    }

    public function search()
    {
        $validData = $this->request->validate([
            'term' => 'required|min:3',
            'productStatus' => 'required',
        ]);

        $searchTerms = explode(' ', $validData['term']);
        $status = $validData['productStatus'];

        $query = Product::query();
        foreach ($searchTerms as $searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('barcode', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        if ($status == "true") {
            $query->available();
        }

        $products = $query->with(['detail'])->get();


        collect($products)->map(function ($product) use ($status) {

            if ($product->getProductVariant()->isEmpty()) {
                $newProduct = $this->makeArrProduct($product);
                $this->products = Collection::make($this->products)->add($newProduct);
            }

            if (!$product->getProductVariant()->isEmpty()) {

                $productVariants = $product->getProductVariant();

                if ($status == "true") {
                    $productVariants = $product->getAvailableProductVariant();
                }

                foreach ($productVariants as $productVariant) {
                    $newProduct = $this->makeArrProduct($productVariant);
                    $this->products->add($newProduct);
                }
            }

        })->all();

        return response()->json($this->products);
    }

    private function makeArrProduct($product): array
    {
        if ($product instanceof Product) {
            return [
                'label' => $product->name,
                'value' => $product->name,
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->barcode,
                'image' => $product->image_url,
                'sales_price' => $product->detail->sales_price ?? 0,
                'is_promotion' => $product->detail->isActivePromotion(),
                'promotion_price' => $product->detail->promotion_price,
                'purchase_price' => $product->detail->purchase_price ?? 0,
                'current_stock' => $product->detail->current_stock ?? 0,
                'variant_id' => null,
                'barcode' => $this->request->input('with') === "barcode" ? \DNS1D::getBarcodePNG($product->barcode, 'C128') : null,
            ];
        } elseif ($product instanceof ProductVariant) {
            return [
                'id' => $product->product->id,
                'name' => $product->product->name . ' - ' . $product->option->valuable->title,
                'label' => $product->product->name . ' - ' . $product->option->valuable->title,
                'code' => $product->code,
                'image' => $product->product->image_url,
                'sales_price' => $product->product->detail->isActivePromotion() ? $product->product->detail->sales_price :$product->sales_price ?? 0,
                'is_promotion' => $product->product->detail->isActivePromotion(),
                'promotion_price' => $product->product->detail->promotion_price,
                'purchase_price' => $product->purchase_price ?? 0,
                'current_stock' => $product->quantity ?? 0,
                'variant_id' => $product->id,
                'barcode' => $this->request->input('with') === "barcode" ? \DNS1D::getBarcodePNG($product->code, 'C128') : null,
            ];
        } else {
            return [];
        }
    }


    public function smartImport()
    {
        return view('product::product.smart-import');
    }

    public function getSmartImportData()
    {
        //validation
        $validData = $this->request->validate([
            'product_id' => 'required'
        ]);

        $response = Http::get('https://api.digikala.com/v1/product/' . $validData['product_id'] . '/' )->json();


        $product = [
            'name' => $response['data']['product']['title_fa'],
            'image_url' => $this->removeResizerImage($response['data']['product']['images']['main']['url'][0]),
            'price' => $response['data']['product']['default_variant']['price']['selling_price'] /10
        ];

        return view('product::product.smart-create',compact('product'));

    }

    public function storeSmartProduct()
    {
        //validation
        $this->request->validate([
            'title' => 'string|required|max:120|unique:products,name',
            'barcode' => 'required|unique:products,barcode',
            'category_id' => 'numeric|required',
            'brand_id' => 'numeric|required',
            'purchase_price' => 'required|integer',
            'sales_price' => 'required|integer|gt:purchase_price',
            'image' => 'required',
            'folder' => 'required',
        ]);
        $client = new \GuzzleHttp\Client();
        $res = $client->get($this->request->image);
        $imageData = (string)$res->getBody();

        $name = \Str::random(5) . '_' . substr($this->request->image, strrpos($this->request->image, '/') + 1);

        $path = 'products/'. $name;

        $fileUploaded = \Storage::disk(config('filesystems.default'))->put($path,$imageData);

        if ($fileUploaded){
            $product = Product::create([
                'name' => $this->request->input('title'),
                'slug' => Common::makeSlug("",str_replace('/','-',$this->request->input('title'))),
                'barcode' => $this->request->input('barcode'),
                'category_id' => $this->request->input('category_id'),
                'brand_id' => $this->request->input('brand_id'),
                'image' => $name,
            ]);

            $product->detail()->create([
                'warehouse_id' => company()->warehouse_id,
                'purchase_price' => $this->request->input('purchase_price'),
                'sales_price' => $this->request->input('sales_price'),
            ]);
        }

        return redirect()->route('products.index');
    }

    private function removeResizerImage($src): string
    {
        $resizerText = '?x-oss-process=image/resize';
        if (Str::contains($src, $resizerText)) {
            return Str::substr($src, 0, strpos($src, $resizerText));
        }
        return $src;
    }


    public function updatePrice()
    {
        $products = Product::query()
            ->whereHas('ProductVariant')->get();


        foreach ($products as $product){
            $sale_price = $product->detail->sales_price;
            // چک میکنه اگر محصول پروموشن بود.
            //if ($product->detail->isActivePromotion()){
                $avg = $product->ProductVariant->avg('sales_price');

                foreach ($product->ProductVariant as $variant){
                    if ( $avg == $variant->sales_price){
                        $variant->update([
                            'sales_price' => $sale_price
                        ]);
                    } else {
                        return back()->with('alertWarning',   'خطا: تفاوت قیمت تنوع در محصول: ' . $product->name);
                    }
                }
           // }

        }

        return back()->with('alertSuccess','بروزرسانی با موفقیت انجام شد.');
    }


    #region barcode print


    public function printIndex()
    {
        return view('product::barcode.print');
    }
    #endregion barcode print
    /*protected function attachAttributeToProduct(Product $product): void
    {
        $attributes = collect($this->request->input('attributes'));

        $attributes->each(function ($attribute) use ($product) {
            if (is_null($attribute['name']) || is_null($attribute['value'])) return;

            $attr = Attribute::firstOrCreate([
                'name' => $attribute['name']
            ]);
            $attValue = $attr->values()->firstOrCreate([
                'display_name' => $attribute['value'],
                'value' => $attribute['value']
            ]);
            $product->attributes()->attach($attr->id, ['value_id' => $attValue->id]);
        });
    }*/

}
