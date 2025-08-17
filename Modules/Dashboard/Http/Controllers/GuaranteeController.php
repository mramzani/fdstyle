<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Entities\Guarantee;
use Throwable;

class GuaranteeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $guarantees = Guarantee::query()->orderByDesc('created_at')->paginate(15);
        return view('dashboard::guarantee.index', compact('guarantees'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();

        $this->validateGuarantee($request);

        try {
            Guarantee::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'link' => $request->input('link'),
            ]);
            \DB::commit();
            return Common::redirectTo('dashboard.guarantees.index', 'alertSuccess', 'گارانتی با موفقیت ایجاد شد.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.guarantees.index', 'alertWarning', 'خطا در ایجاد گارانتی');

        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param Guarantee $guarantee
     * @return Renderable
     */
    public function edit(Guarantee $guarantee)
    {
        return view('dashboard::guarantee.edit', compact('guarantee'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Guarantee $guarantee
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request, Guarantee $guarantee)
    {
        \DB::beginTransaction();

        $this->validateGuarantee($request);

        try {
            $guarantee->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'link' => $request->input('link'),
            ]);
            \DB::commit();
            return Common::redirectTo('dashboard.guarantees.index', 'alertSuccess', 'گارانتی با موفقیت بروزرسانی شد.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.guarantees.index', 'alertWarning', 'خطا در ویرایش گارانتی');

        }
    }

    /**
     * Remove the specified resource from storage.
     * @param Guarantee $guarantee
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Guarantee $guarantee)
    {
        //TODO:: check not relation to product
        //$guarantee->products()->exists();

        \DB::beginTransaction();
        try {
            $guarantee->delete();
            \DB::commit();
            return Common::redirectTo('dashboard.guarantees.index', 'alertWarning', 'گارانتی با موفقیت حذف شد.');
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.guarantees.index', 'alertDanger', 'خطا در حذف گارانتی!');

        }
    }

    private function validateGuarantee(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:5',
            'description' => 'required|string|min:10',
            'link' => 'required',
        ]);
    }
}
