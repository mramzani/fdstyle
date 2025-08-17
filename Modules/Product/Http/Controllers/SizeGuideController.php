<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\SizeGuide;

class SizeGuideController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $size_guides = SizeGuide::paginate(10);

        return view('product::size-guide.index',compact('size_guides'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('product::size-guide.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        //validation
        $this->validateSizeGuide($request);

        if (SizeGuide::query()->where('brand_id',$request->input('brand'))->exists()){
            return back()->with('alertWarning','برای برند انتخاب شده قبلا راهنمای سایز ایجاد شده است.');
        }

        SizeGuide::query()->create([
            'title' => $request->input('title'),
            'brand_id' => $request->input('brand'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('guide-size.index')->with('alertSuccess','راهنمای سایز جدید با موفقیت ایجاد شد.');
    }


    /**
     * Show the form for editing the specified resource.
     * @param SizeGuide $size_guide
     * @return Renderable
     */
    public function edit(SizeGuide $size_guide)
    {
        return view('product::size-guide.edit',compact('size_guide'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param SizeGuide $size_guide
     * @return RedirectResponse
     */
    public function update(Request $request, SizeGuide $size_guide)
    {
        $this->validateSizeGuide($request);

        $size_guide->update([
            'title' => $request->input('title'),
            'brand_id' => $request->input('brand'),
            'description' => $request->input('description'),
        ]);
        return redirect()->route('guide-size.index')->with('alertSuccess','راهنمای سایز جدید با موفقیت بروزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     * @param SizeGuide $size_guide
     * @return RedirectResponse
     */
    public function destroy(SizeGuide $size_guide)
    {
        $size_guide->delete();

        return redirect()->route('guide-size.index')->with('alertSuccess','راهنمای سایز با موفقیت حذف شد.');
    }

    private function validateSizeGuide(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'brand' => ['required'],
            'description' => ['required'],
        ]);
    }
}
