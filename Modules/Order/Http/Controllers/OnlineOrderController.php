<?php

namespace Modules\Order\Http\Controllers;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderHistory;
use Modules\Order\Events\OrderStatusChanged;
use Modules\Order\Events\TrackingNumberSubmitted;
use Modules\Product\Entities\StockHistory;

class OnlineOrderController extends Controller
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return view('order::orders.index');
    }

    public function show(Order $order)
    {
        if ($order->staff_user_id === null) {
            $order->staff_user_id = auth('admin')->user()->id;
            $order->save();
            OrderHistory::new()->log($order, 'مشاهده شده توسط مدیر');
        }
        return view('order::orders.show', compact('order'));
    }

    public function changeOrderStatus(Order $order)
    {
        //validation
        $this->request->validate([
            'status' => 'required'
        ]);
        //change order status
        $order->update([
            'order_status' => $this->request->input('status')
        ]);
        //log
        OrderHistory::new()->log($order, 'تغییر وضعیت سفارش');

        if ($this->request->input('status') == "cancelled" OR $this->request->input('status') == "returned") {
            Common::cancelOnlineOrder($order);
        }

        // check sendSMS exist
        if ($this->request->has('sendSMS')) {
            // event sendsms fire for customer
            event(new OrderStatusChanged($order));
            OrderHistory::new()->log($order, 'ارسال پیامک تغییر وضعیت به مشتری');
        }
        //redirect and show message
        session()->flash('alertSuccess', 'تغییر وضعیت با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function changePaymentOrderStatus(Order $order)
    {
        $this->request->validate([
            'status' => 'required'
        ]);
        $order->update([
            'payment_status' => $this->request->input('status')
        ]);
        OrderHistory::new()->log($order, 'تغییر وضعیت پرداخت');
        session()->flash('alertSuccess', 'تغییر وضعیت با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function storeTrackingNumber(Order $order)
    {
        $this->request->validate([
            'shipping_method' => 'required',
            'tracking_number' => 'required',
        ]);
        $order->update([
            'shipping_method' => $this->request->input('shipping_method'),
            'tracking_number' => $this->request->input('tracking_number'),
        ]);
        //send sms
        event(new TrackingNumberSubmitted($order));
        OrderHistory::new()->log($order, 'ارسال کد رهگیری به مشتری');
        session()->flash('alertSuccess', 'تغییر وضعیت با موفقیت انجام شد.');
        return redirect()->back();
    }
}
