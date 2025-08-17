<?php

namespace Modules\Category\Http\Controllers;

use App\Services\Common;
use App\Services\Uploader\Uploader;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use PHPUnit\Exception;
use Throwable;
use function PHPUnit\Framework\isNull;

class CategoryController extends Controller
{
    /**
     * @var Request $request
     */
    private Request $request;
    public Uploader $uploader;

    public function __construct(Request $request, Uploader $uploader)
    {
        $this->request = $request;
        $this->uploader = $uploader;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $categories = Category::nested()->get();

        return view('category::category.index',
            compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $categories = Category::nested()->get();

        return view('category::category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store()
    {

        // validate store
        $this->validateStore('store', '');
        // upload image
        \DB::beginTransaction();
        try {
            $image = (new Common($this->request))->uploadFile();

            Category::create([
                'title_fa' => $this->request->title_fa,
                'title_en' => $this->request->title_en,
                'slug' => Common::makeSlug($this->request->slug,$this->request->title_en),
                'parent_id' => $this->request->parent_id,
                'image' => $image,
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('category storing error: ' . $exception->getMessage());
            return Common::redirectTo('categories.index', 'alertWarning', 'Error Storing Category');
        }

        // redirect to view and display message
        return Common::redirectTo('categories.index',
            'alertSuccess',
            __('category::categories.new category created successfully.')
        );

    }

    /**
     * Show the form for editing the specified resource.
     * @param Category $category
     * @return Renderable
     */
    public function edit(Category $category)
    {
        $categories = Category::renderAsArray();
        return view('category::category.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param Category $category
     * @return RedirectResponse
     * @throws \Exception
     * @throws Throwable
     */
    public function update(Category $category)
    {
        // validate update
        $this->validateStore('update', $category);
        \DB::beginTransaction();
        try {
            $image = (new Common($this->request))->uploadFile();

            // update Category
            if ($category->id == $this->request->parent_id)
                return Common::redirectTo('categories.index',
                    'alertWarning',
                    trans('category::categories.The child category should not be equal to the parent category'));

            $category->update([
                'title_fa' => $this->request->title_fa,
                'title_en' => $this->request->title_en,
                'slug' => Common::makeSlug($this->request->slug,$this->request->title_en),
                'parent_id' => $this->request->parent_id,
                'variant_id' => $this->request->input('variant'),
                'attribute_group_id' => $this->request->input('attribute_group_id'),
                'merchant_commission' => $this->request->input('merchant_commission') ?? $category->merchant_commission,
                'image' => $image,
                'seo_title' => $this->request->seo_title,
                'seo_description' => $this->request->seo_description,
                'description' => $this->request->description,
            ]);
            $category->save();


            if (!auth('admin')->user()->roles()->whereIn('name',['technicalAdmin'])->exists()
                        and array_key_exists('merchant_commission',$category->getChanges())){
                throw new \Exception('شما دسترسی برای تغییر مقدار کمیسون را ندارید');
            }
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('categories.index', 'alertWarning', 'Error Updating Category ' . $exception->getMessage());

        }

        // redirect to view and display message

        return Common::redirectTo('categories.index',
            'alertSuccess',
            trans('category::categories.category updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Category $category
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Category $category)
    {
        \DB::beginTransaction();
        try {
            if ($category->products()->exists() || $category->child()->exists())
                return Common::redirectTo('categories.index', 'alertWarning',
                    trans("category::categories.you can't delete this category!"));

            $category->delete();
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Deleting Category: '. $exception->getMessage());
            return Common::redirectTo('categories.index', 'alertWarning', 'Error Deleting Category');
        }
        return Common::redirectTo('categories.index',
            'alertSuccess',
            trans('category::categories.category has deleted successfully.'));
    }

    private function validateStore(string $method, $category = null): void
    {
        if ($method == 'store') {
            $this->request->validate([
                'title_fa' => 'required|persian_alpha|min:3',
                'title_en' => 'required|string|persian_not_accept|min:3',
                'slug' => 'nullable|string|unique:categories,slug',
                'parent_id' => 'nullable|integer',
                'image' => 'nullable|image',
            ]);
        } elseif ($method == 'update') {
            $this->request->validate([
                'title_fa' => 'required|persian_alpha|min:3',
                'title_en' => 'nullable|string|persian_not_accept|unique:categories,title_en,' . $category->id,
                'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
                'parent_id' => 'nullable|integer',
                'variant' => 'nullable|integer',
                'attribute_group_id' => 'nullable|integer',
                'merchant_commission' => 'integer|min:0|max:99',
                'image' => 'nullable|image',
            ]);
        }

    }

}
