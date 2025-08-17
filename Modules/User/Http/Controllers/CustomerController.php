<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\User\Entities\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('user::customer.list');
    }

    /**
     * Show the specified resource.
     * @param Customer $customer
     * @return Renderable
     */
    public function show(Customer $customer)
    {
         $customer->load('detail','roles');
        return view('user::customer.show',compact('customer'));
    }

    public function getCustomer(Request $request)
    {
        $term = $request->input('keyword');
        $customers = Customer::search(\DB::raw("concat(first_name, ' ', last_name)"),$term)
            ->orWhere('mobile', 'LIKE', '%' . $term . '%')
            ->orderBy('id', 'DESC')
            ->paginate(25);

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
