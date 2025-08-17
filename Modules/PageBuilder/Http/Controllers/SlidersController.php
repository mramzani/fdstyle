<?php

namespace Modules\PageBuilder\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\PageBuilder\Entities\Slider;
use Modules\PageBuilder\Entities\SliderItem;

class SlidersController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()->orderBy('created_at')->paginate(10);
        return view('pagebuilder::sliders.index',compact('sliders'));
    }

    public function create()
    {
        return view('pagebuilder::sliders.create');
    }

    public function store(Request $request)
    {
        $this->sliderValidate($request);

        $slider = Slider::query()->create([
            'name' => $request->input('name'),
            'key' => str_replace(' ', '-', $request->input('key')),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('dashboard.sliders.edit', $slider->id)->with('alertSuccess', 'اسلایدر جدید با موفقیت ایجاد شد.');
    }

    public function edit(Slider $slider)
    {
        return view('pagebuilder::sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        //convertToJalali
        $request->validate([
            'name' => 'required',
            'key' => 'required|persian_not_accept|unique:sliders,key,'. $slider->id,
        ]);

        $slider->update([
            'name' => $request->input('name'),
            'key' => str_replace(' ', '-', $request->input('key')),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('dashboard.sliders.edit', $slider->id)->with('alertSuccess', 'اسلایدر با موفقیت بروزرسانی شد.');
    }

    public function destroy(Slider $slider)
    {
        try {
            $slider->deleteOrFail();
            return redirect()->back()->with('alertSuccess', 'اسلایدر با موفقیت حذف شد.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('alertDanger', $exception->getMessage());
        }
    }

    public function storeItem(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'string|nullable',
            'link' => 'string|nullable',
            'priority' => 'int|required',
            'description' => 'string|nullable',
            'slider_lg' => 'required',
            'slider_md' => 'required',
        ]);


        $img_url = implode(',',[$request->input('slider_lg'),$request->input('slider_md')]);


        SliderItem::create([
            'title' => $request->input('title'),
            'slider_id' => $slider->id,
            'link' => $request->input('link'),
            'priority' => $request->input('priority'),
            'image' => $img_url,
            'description' => $request->input('description'),
        ]);

        return redirect()->route('dashboard.sliders.edit', $slider->id)->with('alertSuccess', 'تصویر با موفقیت افزوده شد.');
    }

    public function destroyItem(SliderItem $item)
    {
        try {
            $item->deleteOrFail();
            return redirect()->back()->with('alertSuccess', 'تصویر با موفقیت حذف شد.');
        } catch (\Exception $exception) {
            return redirect()->back()->with('alertDanger', $exception->getMessage());
        }
    }

    private function sliderValidate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'key' => 'required|persian_not_accept|unique:sliders,key',
        ]);
    }


}
