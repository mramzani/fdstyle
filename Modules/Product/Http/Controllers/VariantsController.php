<?php

namespace Modules\Product\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Variant;
use Modules\Product\Entities\VariantValue;
use Throwable;

class VariantsController extends Controller
{
    public function index()
    {
        $categories = Category::with('variant')
            ->whereNotNull(['variant_id'])
            ->latest()->paginate(10);

        $variantValue = VariantValue::with('variant', 'category')
            ->latest()->paginate(10);

        return view('product::product-variant.index', compact('variantValue', 'categories'));
   }

    /**
     * @throws Throwable
     */
    public function variantAttach(Request $request)
    {

        $request->validate([
            'category' => 'required',
            'variant' => 'required',
        ]);
        \DB::beginTransaction();
        try {
            $variant = $this->getFirstVariant('id',$request->input('variant'));

            $category = $this->getFirstCategory($request->input('category'));

            if (is_null($category->variant_id)) {
                $category->update([
                    'variant_id' => $variant->id
                ]);
                $category->save();
            } else {
                throw new \RuntimeException('قبلا برای این دسته‌بندی ویژگی تعریف شد');
            }
            \DB::commit();

        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error attaching attribute to Category with Error: ' . $exception->getMessage());
            return Common::redirectTo('variants.index', 'alertWarning', $exception->getMessage());
        }

        return Common::redirectTo('variants.index', 'alertSuccess', 'ویژگی به دسته‌بندی متصل شد!');
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function emptyVariant(Category $category) :RedirectResponse
    {
        if ($category->values()->exists()) {
            return Common::redirectTo('variants.index', 'alertWarning', 'شما نمی‌توانید این تنوع را از دسته‌بندی جدا کنید!');
        }
        $category->variant_id = null;
        $category->save();
        return Common::redirectTo('variants.index', 'alertSuccess', 'تنوع از دسته‌بندی جدا شد!');
    }

    #region private function

    /**
     * get first Attribute
     * @param string $type
     * @param $value
     * @return Variant
     */
    private function getFirstVariant(string $type, $value)
    {
        return Variant::firstVariantByType($type, $value);
    }

    /**
     * get first category
     * @param $value
     * @return Category
     */
    private function getFirstCategory($value)
    {
        return Category::firstCategoryById($value);
    }

    #endregion
}
