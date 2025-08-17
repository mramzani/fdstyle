<?php

namespace Modules\Product\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Color;
use Modules\Product\Entities\Size;
use Modules\Product\Entities\Variant;
use Modules\Product\Entities\VariantValue;
use Throwable;

class VariantValuesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {

        $validData = $this->validateAttribute($request);

        \DB::beginTransaction();
        try {
            $variant = $this->getFirstVariant('type',$request->input('variant_type'));

            $category = $this->getFirstCategory($validData['category_id']);

            if (is_null($category->variant_id)) {
                throw new \InvalidArgumentException('ابتدا برای دسته‌بندی انتخاب شده تنوع مجاز را تعیین کنید');
            }

            if ($category->variant_id != $variant->id) {
                throw new \InvalidArgumentException('ویژگی انتخاب شده برای این دسته‌بندی مجاز نیست!');
            }

            $this->addVariantValue($validData, $variant);

            \DB::commit();
        }
        catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error creating Attribute with error => ' . $exception->getMessage());
            return Common::redirectTo('variants.index', 'alertWarning', $exception->getMessage());
        }

        return Common::redirectTo('variants.index', 'alertSuccess', trans('product::attributes.new attribute created successfully.'));

    }

    /**
     * Remove the specified resource from storage.
     * @param VariantValue $value
     * @return RedirectResponse
     */
    public function destroy(VariantValue $value) : RedirectResponse
    {
        if ($value->ProductVariant()->exists())
            return Common::redirectTo('variants.index', 'alertWarning', trans("product::attributes.you can't delete this value!"));

        $value->delete();

        return Common::redirectTo('variants.index', 'alertSuccess', trans('product::attributes.attribute deleted successfully.'));
    }

    /**
     * get category for attributes.get-categories route
     * @param Request $request
     * @return Response
     */
    public function getCategories(Request $request)
    {
        $values = [];
        if ($request->input('type') == 'color'){
            $values = Color::all();
        }
        else if ($request->input('type') == 'size'){
            $values = Size::all();
        }
        return response([
            'values' => $values,
        ]);
    }


    #region private function

    /**
     * @param $request
     * @return mixed
     */
    private function validateAttribute($request)
    {
        return $request->validate([
            'category_id' => 'required',
            'variant_type' => 'required',
            'value' => 'required',
        ]);
    }

    /**
     * @param $data
     * @param $variant
     * @return void
     */
    private function addVariantValue($data, $variant)
    {

        foreach ($data['value'] as $value){

            $valuable = VariantValue::findValuableType($value,$data['variant_type']);

            if ($valuable->VariantValue()->where('category_id', $data['category_id'])->exists()) {
                throw new \InvalidArgumentException('مقادیر تکراری می‌باشد');
            }

            $valuable->VariantValue()
                ->create(['variant_id' => $variant->id, 'category_id' => $data['category_id']]);
        }

    }

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
