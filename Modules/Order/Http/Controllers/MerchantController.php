<?php

namespace Modules\Order\Http\Controllers;

use App\Services\Payment\Gateway\Zibal\ZibalCheckout;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\SubMerchants;
use Throwable;

class MerchantController extends Controller
{
    private $zibalCheckout;

    public function __construct(ZibalCheckout $zibalCheckout)
    {
        $this->zibalCheckout = $zibalCheckout;
    }

    public function index()
    {
        $report = $this->zibalCheckout->reportTransaction();

        $merchants = SubMerchants::orderBy('id', 'asc')->paginate(10);

        return view('order::merchants.index', compact('merchants', 'report'));
    }

    /**
     * @throws Throwable
     */
    public function request(SubMerchants $merchant)
    {
        \DB::beginTransaction();
        try {
            $response = $this->zibalCheckout->requestCheckout($merchant);

            if ($response['result'] == $this->zibalCheckout::CHECKOUT_SUCCESS) {
                $merchant->balance = 0;
                $merchant->save();
                \DB::commit();
            } elseif ($response['result'] == $this->zibalCheckout::CHECKOUT_FAILED) {
                throw new \Exception($response['message']);
            }

            return back()->with('alertSuccess', $response['message']);

        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return back()->with('alertWarning', $exception->getMessage());
        }
    }

}
