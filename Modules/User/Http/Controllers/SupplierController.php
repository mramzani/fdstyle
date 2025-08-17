<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('user::supplier.list');
    }


    /**
     * Show the specified resource.
     * @param Supplier $supplier
     * @return Renderable
     */
    public function show(Supplier $supplier)
    {
        $supplier->load('detail');
        return view('user::supplier.show',compact('supplier'));
    }


    public function getSupplier(Request $request)
    {
        $term = $request->input('keyword');
        $customers = Supplier::search(\DB::raw("concat(first_name, ' ', last_name)"),$term)
            ->orWhere('mobile', 'LIKE', '%' . $term . '%')
            ->orderBy('created_at', 'DESC')
            ->get();

        $updatedCustomers = $customers->collect()
            ->map(function ($customer){
                return collect($customer->toArray())
                    ->only(['id','first_name', 'last_name','mobile'])
                    ->all();
            });

        return response()->json([
            'data' => $updatedCustomers->toArray(),
        ]);
    }
}
