<?php

namespace Modules\PageBuilder\Http\Controllers;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use Modules\PageBuilder\Entities\Banner;
use Modules\PageBuilder\Entities\Home;
use Modules\PageBuilder\Entities\Offers;
use Modules\PageBuilder\Entities\Slider;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $homes = Home::query()->paginate(10);

        return view('pagebuilder::homes.index',compact('homes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pagebuilder::homes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_at' => 'required',
            'expire_at' => 'required',
            'status' => 'required',
        ]);

        if ($request->has('is_default')){
            Home::resetDefaultHome();
        }


        $home = Home::query()->create([
            'name' => $request->input('name'),
            'start_at' => Common::convertDateTimeToGregorian($request->input('start_at')),
            'expire_at' => Common::convertDateTimeToGregorian($request->input('expire_at')),
            'status' => $request->input('status'),
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('dashboard.home.edit', $home->id)->with('alertSuccess', 'صفحه جدید با موفقیت ایجاد شد.');
    }

    public function edit(Home $home)
    {
        return view('pagebuilder::homes.edit', compact('home'));
    }

    public function update(Request $request, Home $home)
    {
        $request->validate([
            'name' => 'required',
            'start_at' => 'required',
            'expire_at' => 'required',
            'status' => 'required',
        ]);


        if ($request->has('is_default')){
            Home::resetDefaultHome();
        }

        $home->update([
            'name' => $request->input('name'),
            'start_at' => Common::convertDateTimeToGregorian($request->input('start_at')),
            'expire_at' => Common::convertDateTimeToGregorian($request->input('expire_at')),
            'status' => $request->input('status'),
            'is_default' => $request->has('is_default'),
        ]);
        $home->save();

        return redirect()->route('dashboard.home.index')->with('alertSuccess', 'صفحه با موفقیت بروزرسانی شد.');
    }

    public function destroy(Home $home)
    {
        //$home->delete();
        if (Home::all()->count() == 1 ){
            return back()->with('alertWarning', 'سایت نمیتوانید بدون چیدمان باشد. لطفا یک صفحه رزرو ایجاد کنید');
        }
        $home->delete();
        return back()->with('alertSuccess', 'صفحه با موفقیت حذف شد.');
    }

    public function getItem(Request $request)
    {
        $items = [];
        if ($request->input('item_type') == "banner") {
            $banners = Banner::query()->where('status','published')
                ->select(['id','name','type'])
                ->get();
            $items = collect($banners)
                ->map(function ($banner){
                return [
                    'id' => $banner->id,
                    'name' => $banner->name,
                    'type' => $banner->type
                ];
            })->toArray();

        }
        elseif ($request->input('item_type') == "category") {
            //$categories = Category::query()->select(['id','title_fa'])->get();
            /*$items = collect($categories)
                ->map(function ($category){
                    return [
                        'id' => $category->id,
                        'name' => $category->title_fa,
                        'type' => '',
                    ];
                })->toArray();*/
            $categories = Category::nested()->get();
            $level = 0;
            return view('product::product.secondary_category',compact('categories','level'));
        }
        elseif ($request->input('item_type') == "slider") {
            $sliders = Slider::query()->where('status','published')->select(['id','name'])->get();
            $items = collect($sliders)
                ->map(function ($slider){
                    return [
                        'id' => $slider->id,
                        'name' => $slider->name,
                        'type' => '',
                    ];
                })->toArray();
        }
        elseif ($request->input('item_type') == "offer"){
            $offers = Offers::query()->where('status','published')->select(['id','title'])->get();
            $items = collect($offers)
                ->map(function ($offer){
                    return [
                        'id' => $offer->id,
                        'name' => $offer->title,
                        'type' => '',
                    ];
                })->toArray();
        }
        return $items;
    }
}
