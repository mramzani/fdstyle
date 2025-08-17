<?php

namespace Modules\Tax\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Tax\Entities\Tax;
use Modules\Tax\Http\Requests\StoreRequest;
use Modules\Tax\Http\Requests\UpdateRequest;
use Throwable;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $taxes = Tax::all();
        return view('tax::tax.list', compact('taxes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        \DB::beginTransaction();
        try {
            Tax::create($request->safe()->only(['name', 'rate']));
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error storing Tax: ' . $exception->getMessage());
            return Common::redirectTo('taxes.index', 'alertWarning', 'Error storing Tax');
        }

        return back()->with('alertSuccess', trans('tax::taxes.New tax successfully created.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tax $tax
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Tax $tax)
    {
        return view('tax::tax.edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Tax $tax
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdateRequest $request, Tax $tax): RedirectResponse
    {
        \DB::beginTransaction();
        try {
            $tax->update($request->only(['name', 'rate']));
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Updating Tax: ' . $exception->getMessage());
            return Common::redirectTo('taxes.index', 'alertWarning', 'Error Updating Tax');

        }

        return redirect()->route('dashboard.taxes.index')
            ->with('alertSuccess', trans('taxes.Tax has been successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tax $tax
     * @return RedirectResponse
     */
    public function destroy(Tax $tax): RedirectResponse
    {
        $tax->delete();
        return back()->with('alertSuccess', trans('taxes.Tax was successfully removed.'));
    }
}
