<?php

namespace Modules\Order\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Order\Entities\Payment;
use Throwable;

class PaymentController extends Controller
{

    /**
     * @param string $paymentType
     * @return RedirectResponse|View
     */
    public function index(string $paymentType)
    {
        if ($paymentType == "in" || $paymentType == "out") {
            $payment_type = $paymentType == 'in' ? 'لیست تراکنش دریافتی' : 'لیست تراکنش پرداختی';
            return view('order::payments.index', compact('payment_type', 'paymentType'));
        } else {
            return redirect()->route('dashboard.index');
        }
    }


    /**
     * @throws Throwable
     */
    public function destroy(Payment $payment)
    {

        \DB::beginTransaction();
        try {
            $payments = Payment::select('order_id')->where('id', $payment->id)->get();
            $userId = $payment->user_id;
            $warehouseId = $payment->warehouse_id;
            $payment->delete();

            if (count($payments) > 0) {
                foreach ($payments as $pay){
                    Common::updateOrderAmount($pay->order);
                }
            } else {
                Common::updateUserAmount($userId,$warehouseId);
            }

            \DB::commit();
        }
        catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('error deleting payment: ' . $exception->getMessage());
            return Common::redirectTo('dashboard.payment.index', 'alertWarning', 'error deleting payment: ' . $exception->getMessage());

        }
        return redirect()->back()->with('alertSuccess', 'پرداخت با موفقیت حذف شد!');
    }


    public function transactions()
    {
        $payments = Payment::where('payment_mode_id',5)->paginate(10);

        return view('order::transactions.list',compact('payments'));
    }

}
