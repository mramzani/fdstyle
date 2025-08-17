<?php

namespace Modules\Brand\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Entities\Brand;
use Throwable;

class BrandController extends Controller
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('brand::brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('brand::brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws Throwable
     */
    public function store()
    {
        $this->validateStore();

        \DB::beginTransaction();
        try {
            $imgName = (new Common($this->request))->uploadFile();

            Brand::create([
                'title_fa' => $this->request->title_fa,
                'title_en' => $this->request->title_en,
                'slug' => Common::makeSlug($this->request->slug,$this->request->title_en),
                'image' => $imgName
            ]);

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('brands.index', 'alertWarning', 'خطایی در ایجاد برند رخ داده است!');
        }


        return Common::redirectTo('brands.index', 'alertSuccess', __('brand::brands.new brand created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Brand $brand
     * @return Renderable
     */
    public function edit(Brand $brand)
    {
        return view('brand::brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     * @param Brand $brand
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Brand $brand)
    {
        $this->validateUpdate($brand);
        \DB::beginTransaction();
        try {
            $imgName = (new Common($this->request))->uploadFile();
            $brand->update([
                'title_fa' => $this->request->title_fa,
                'title_en' => $this->request->title_en,
                'seo_title' => $this->request->seo_title,
                'seo_description' => $this->request->seo_description,
                'description' => $this->request->description,
                'slug' => Common::makeSlug($this->request->slug,$this->request->title_en),
                'image' => $imgName
            ]);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('brands.index', 'alertWarning', 'خطایی در بروزرسانی برند رخ داده است.');
        }

        return Common::redirectTo('brands.index', 'alertSuccess', __('brand::brands.brand updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Brand $brand
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Brand $brand)
    {
        \DB::beginTransaction();
        try {
            if ($brand->products()->exists()) return Common::redirectTo('brands.index', 'alertWarning', trans("brand::brands.you can't delete this brand!"));
            $brand->delete();
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('brands.index', 'alertWarning', 'خطایی در حذف برند رخ داده است.');
        }

        return Common::redirectTo('brands.index', 'alertSuccess', trans('brand::brands.brand deleted successfully.'));
    }

    private function validateStore(): void
    {
        $this->request->validate([
            'title_fa' => 'required|string|min:2|unique:brands,title_fa|persian_alpha_eng_num',
            'title_en' => 'required|string|min:2|unique:brands,title_en|persian_not_accept',
            'slug' => 'nullable|string|min:3',
            'image' => 'nullable|image',
        ]);
    }

    private function validateUpdate($brand): void
    {
        $this->request->validate([
            'title_fa' => 'required|string|min:2|persian_alpha_eng_num|unique:brands,title_fa,' . $brand->id,
            'title_en' => 'required|string|min:2|persian_not_accept|unique:brands,title_en,' . $brand->id,
            'slug' => 'required|string|min:3',
            'image' => 'nullable|image',
        ]);
    }

}
