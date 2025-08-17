<?php

namespace Modules\PageBuilder\Http\Controllers;

use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Category\Entities\Category;
use Modules\PageBuilder\Entities\Banner;
use Modules\PageBuilder\Entities\Home;
use Modules\PageBuilder\Entities\HomeItem;
use Modules\PageBuilder\Entities\Offers;
use Modules\PageBuilder\Entities\Slider;
use Throwable;

class HomeItemController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(Request $request, Home $home)
    {
        \DB::beginTransaction();
        try {
            $request->validate([
                'title' => 'string|required',
                'item_type' => 'required',
                'rowable_id' => 'required',
                'priority' => 'required|integer',
            ]);

            if ($home->items()->where('priority', $request->input('priority'))->exists()) {
                throw new \Exception('الویت نمایش برای این چیدمان تکراری می‌باشد.');
            }

            $rowable = null;
            if ($request->input('item_type') == "banner") {
                $rowable = Banner::where('id', $request->input('rowable_id'))->firstOrFail();
            } elseif ($request->input('item_type') == "category") {
                $rowable = Category::where('id', $request->input('rowable_id'))->firstOrFail();
            } elseif ($request->input('item_type') == "slider") {
                $rowable = Slider::where('id', $request->input('rowable_id'))->firstOrFail();
            }elseif ($request->input('item_type') == "offer") {
                $rowable = Offers::where('id', $request->input('rowable_id'))->firstOrFail();
            }
            if (!is_null($rowable)) {
                $rowable->homeItems()->create([
                    'home_id' => $home->id,
                    'priority' => $request->input('priority'),
                    'title' => $request->input('title')
                ]);
            }

            \DB::commit();

            return back()->with('alertSuccess', 'آیتم جدید با موفقیت ایجاد شد.');

        } catch (\Exception $exception) {
            \DB::rollBack();

            \Log::warning($exception->getMessage());

            return back()->with('alertWarning', 'خطا در ایجاد آیتم : ' . $exception->getMessage());

        }

    }

    public function destroy(HomeItem $homeItem)
    {
        try {
            $homeItem->delete();
            return back()->with('alertSuccess', 'آیتم‌ مورد نظر با موفقیت حذف شد.');

        } catch (\Exception $e) {
            return back()->with('alertWarning', $e->getMessage());
        }
    }
}
