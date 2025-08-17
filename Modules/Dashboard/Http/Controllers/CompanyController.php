<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Entities\Company;
use Throwable;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function edit()
    {
        $company = \company();

        return view('dashboard::company.index',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Company $company
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request,Company $company)
    {
        $validData = $this->validateUpdateCompany($request);

        \DB::beginTransaction();
        try {
            $imgName = (new Common($request))->uploadFile();

            $validData['logo'] = $imgName;

            $company = \company();

            $company->update($validData);
            //session()->forget('company');
            //session()->forget('unit');
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('Error Updating Company: ' . $exception->getMessage());
            return Common::redirectTo('dashboard.company.edit', 'alertWarning', 'Error Updating Company');
        }

        return back()->with('alertSuccess', trans('dashboard::company.Company Information Updated Successfully.'));
    }

    private function validateUpdateCompany(Request $request)
    {
        return $request->validate([
            'site_title' => 'required|string',
            'desc' => 'string|nullable',
            'email' => 'required|email|nullable',
            'phone' => 'nullable',
            'address' => 'string|nullable',
            'warehouse_id' => 'required',
            'unit_id' => 'required',
        ]);
    }

}
