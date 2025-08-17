<?php

namespace Modules\Product\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Exceptions\InvalidAttributeException;
use Modules\Product\Exceptions\InvalidVariantException;
use Throwable;

class ProductVariantsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Product $product
     * @return Renderable
     */
    public function index(Product $product)
    {
        return view('product::variant.index', compact('product'));
    }


    /**
     * @throws Throwable
     */
    public function destroy(ProductVariant $productVariant)
    {

        \DB::beginTransaction();
        try {

            if ($productVariant->orders()->exists() || $productVariant->adjustments()->exists())
                throw new \RuntimeException('به دلیل موجود بودن محصول این تنوع قابل حذف نیست');

            $productVariant->delete();
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();

            \Log::warning('Error deleting product variant: ' . $exception->getMessage());

            return back()->with('alertWarning', 'Error deleting product variant: ' . $exception->getMessage());

        }
        //$variant->productSku->delete();


        return back()->with('alertSuccess', 'تنوع محصول با موفقیت حذف شد.');

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function store(Request $request, Product $product)
    {


        $request->validate([
            'code' => 'required|unique:product_variants,code',
            'sales_price' => 'required',
            'purchase_price' => 'required',
            'productVariant.variant' => 'required',
            'productVariant.value' => 'required_unless:productVariant.variant,no_color_no_size',
        ]);

        $variant = $this->generateVariant($request);

        try {
            $product->addVariant($variant);
        } catch (\Exception $exception) {
            return back()->with('alertWarning', $exception->getMessage());
        }

        return back()->with('alertSuccess', trans('product::attributes.product variation updated successfully.'));
    }

    /**
     * @param Request $request
     * @return array
     */
    private function generateVariant(Request $request)
    {
        $variant['code'] = $request->input('code');
        $variant['purchase_price'] = $request->input('purchase_price');
        $variant['sales_price'] = $request->input('sales_price');
        $variant['productVariant'] = [$request->input('productVariant')];

        return $variant;
    }
}
