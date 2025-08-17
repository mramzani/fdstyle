<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Http\Requests\StoreRequest;
use Modules\Warehouse\Http\Requests\UpdateRequest;
use Throwable;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('warehouse::warehouse.list', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(StoreRequest $storeRequest)
    {
        //$storeRequest->merge(['user_id'=> auth()->user()->id]);

        \DB::beginTransaction();
        try {
            Warehouse::create($storeRequest->only(['name', 'phone', 'status', 'address']));
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Creating Warehouse: ' . $exception->getMessage());
            return Common::redirectTo('warehouses.index', 'alertWarning', 'Error Creating Warehouse');
        }

        return back()->with('alertSuccess', trans('warehouse::warehouses.New Warehouse successfully created.'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Warehouse $warehouse
     * @return View
     */
    public function edit(Warehouse $warehouse): View
    {
        return view('warehouse::warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateRequest $updateRequest
     * @param Warehouse $warehouse
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdateRequest $updateRequest, Warehouse $warehouse): RedirectResponse
    {
        \DB::beginTransaction();
        try {
            $updateRequest->input('status') == 'on'
                ? $updateRequest->merge(['status' => 'active'])
                : $updateRequest->merge(['status' => 'deActive']);
            $warehouse->update($updateRequest->only(['name', 'phone', 'status', 'address']));
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Updating Warehouse: ' . $exception->getMessage());
            return Common::redirectTo('warehouses.index', 'alertWarning', 'Error Updating Warehouse');
        }

        return redirect()->route('dashboard.warehouses.index')
            ->with('alertSuccess', trans('warehouse::warehouses.Warehouse has been successfully updated.'));

    }

    /**
     * Remove the specified resource from storage.
     * @param Warehouse $warehouse
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Warehouse $warehouse)
    {
        \DB::beginTransaction();
        try {
            if ($warehouse->id == 1)
                return back()->with('alertWarning', 'این انبار غیرقابل حذف می‌باشد!');

            if ($warehouse->ProductDetail()->exists())
                return back()->with('alertWarning', 'حذف انبار انجام نشد! این انبار دارای محصول می‌باشد');

            $warehouse->delete();
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Deleting Warehouse: ' . $exception->getMessage());
            return Common::redirectTo('warehouses.index', 'alertWarning', 'Error Deleting Warehouse');
        }
        return back()->with('alertSuccess', trans('warehouse::warehouses.Warehouse was successfully removed.'));
    }
}
