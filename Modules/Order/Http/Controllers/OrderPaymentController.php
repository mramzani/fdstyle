<?php

namespace Modules\Order\Http\Controllers;

use App\Services\Common;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Payment;
use Throwable;

class OrderPaymentController extends Controller
{

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'date' => 'required',
            'paying_amount' => 'required',
            'payment_mode_id' => 'required',
            'reference_id' => 'nullable',
            'notes' => 'nullable',
        ]);

        \DB::beginTransaction();
        try {
            $order = Order::find($request->order_id);
            $paymentType = "";
            if ($order->order_type == 'purchases' || $order->order_type == 'purchase-returns') {
                $paymentType = "out";
            } else if ($order->order_type == 'sales' || $order->order_type == 'sales-returns') {
                $paymentType = "in";
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'warehouse_id' => $order->warehouse_id,
                'payment_type' => $paymentType,
                'date' => Common::convertToJalali($request->input('date')),
                'amount' => $request->input('paying_amount'),
                'paid_amount' => $request->input('paying_amount'),
                'payment_mode_id' => $request->input('payment_mode_id'),
                'user_id' => $order->user_id,
                'reference_id' => $request->input('reference_id'),
                'notes' => $request->input('notes'),
            ]);


            $payment->payment_number = Common::generateTransactionNumber('payment-'.$paymentType, $payment->id);
            $payment->save();

            Common::updateOrderAmount($payment->order);
            \DB::commit();

        } catch (\Exception $exception) {

            \DB::rollBack();
            \Log::warning($exception->getMessage());

            return \Response::json([
                'message' => $exception->getMessage()
            ], 422);

        }

        return \Response::json([
            'status' => true
        ]);

    }


}
