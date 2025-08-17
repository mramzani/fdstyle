<?php

namespace App\Services\Payment;

use App\Services\Common;
use App\Services\Cost\Facades\Cost;
use App\Services\Payment\Gateway\Contract\GatewayInterface;
use App\Services\Payment\Gateway\Zibal;
use Illuminate\Http\Request;
use Modules\Coupon\Entities\Coupon;
use Modules\Front\Events\OnlineOrderRegistered;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderHistory;
use Modules\Order\Entities\SubMerchants;

class Transaction
{
    private string $defaultGateway;
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->defaultGateway = config('front.default_online_gateway');
    }

    public function start(Order $order)
    {
        return $this->gatewayFactory($this->defaultGateway)->pay($order,$this->defaultGateway,Cost::getTotalCosts());
    }

    public function verify()
    {
        $order = Order::findOrFail($this->request->orderId);

        $result = $this->gatewayFactory($this->request->gateway)->verify($this->request, $order->total);

        if ($result['status'] === GatewayInterface::TRANSACTION_SUCCESS) {
            // confirm payment
           // $order = Order::findOrFail($result['order_id']);
            $this->confirmPayment($result,$order);
            // order completed
            $this->orderCompleted($order);

        } //elseif ($result['status'] === GatewayInterface::TRANSACTION_FAILED) {

        $result['order'] = $order;
        //}


        return $result;
    }

    /**
     * @param $gateway
     * @return GatewayInterface
     */
    private function gatewayFactory($gateway): GatewayInterface
    {
        $gateway = [
            'zibal' => ZiBal::class,
        ][$gateway];
        return resolve($gateway);
    }

    /**
     * @param $result
     * @param Order $order
     * @return void
     */
    private function confirmPayment($result,Order $order)
    {
        $order->payment->confirm($result['refNum'],$result['gateway'],$result['description']);
    }

    private function orderCompleted(Order $order)
    {
        $this->changeStatus($order);
        // normalize quantity
        Common::syncOnlineOrder($order);
        // change status
        Common::updateOnlineOrderAmount($order);
        // log for payment with customer
        OrderHistory::new()->log($order,'پرداخت سفارش توسط مشتری','customer');

        // fire Order Register Event => send alert to telegram
        // fire Change order Status => send sms to customer and change status
         event(new OnlineOrderRegistered($order));
        // log for send sms to customer
        OrderHistory::new()->log($order,'ارسال پیامک تغییر وضعیت به مشتری','customer');

        // increment merchant balance
        SubMerchants::incrementBalance($order,$this->fee($order->total));

        // clear coupon session if exists
        if (session()->has('coupon')) {
            $coupon = Coupon::find(session()->get('coupon'))->first();
            $coupon->increment('used_qty', 1);
            session()->forget('coupon');
        }

    }

    private function changeStatus(Order $order): void
    {
        $order->order_status = "processing";
        $order->profit -= $this->fee($order->total);
        $order->save();
    }

    public function fee(int $amount): int
    {
        return $this->gatewayFactory($this->defaultGateway)->fee($amount);
    }
}
