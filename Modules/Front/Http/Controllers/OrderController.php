<?php

namespace Modules\Front\Http\Controllers;

use App\Services\Order\OrderRegister;
use App\Services\Payment\Transaction;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\User\Helper\Helper;
use Throwable;

class OrderController extends BaseController
{
    private OrderRegister $orderRegister;
    private Transaction $transaction;

    public function __construct(OrderRegister $orderRegister, Transaction $transaction)
    {
        $this->orderRegister = $orderRegister;
        $this->transaction = $transaction;
    }

    /*
     * online sales list
     */
    public function list()
    {
        $orders = $this->orderByStatus(['processing','preparing','shipping']);

        return view('front::orders.list', compact('orders'));
    }

    /*
     * offline sales list
     */
    public function sales()
    {
        $customer = Helper::getCustomer();
        $sales = Order::where('order_type', 'sales')
            ->where('user_id', $customer->id)
            ->latest()->paginate(10);
        return view('front::sales.list', compact('sales'));
    }

    public function show(Order $order)
    {

        if ($order->user_id != auth('customer')->id()) {
            \request()->session()->flash('alertWarning', 'فاکتور موجود نیست');
            return redirect()->route('front.user.sales');
        }

        if ($order->order_type == "online") {
            return view('front::orders.detail', compact('order'));
        }

        return view('order::sales.pos-invoice', compact('order'));
    }

    /**
     * @throws Throwable
     */
    public function store()
    {
        //TODO: validation

        if (!auth('customer')->user()->addresses()->get()->contains('is_default', true)) {
            //session()->flash('NOT_AVAILABLE_ADDRESS','شما آدرس ندارید');
            return back();
        }
        return $this->orderRegister->checkout();

    }

    public function verify()
    {
        //TODO:: transfer to PaymentController
        /**
         * @var Order|null $result ['order']
         */
        $result = $this->transaction->verify();

        return view('front::payment.callback', compact('result'));
    }

    public function getOrder(string $status)
    {
        $orders = $this->orderByStatus(explode(',',$status));

        $orders = collect($orders)
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'invoice_number' => $order->invoice_number,
                    'order_date' => $order->date_time,
                    'total' => number_format($order->total),
                    'order_status' => $order->online_order_status,
                ];
            })->toArray();
        return view('front::orders.order_table', compact('orders'));
    }


    private function orderByStatus(array $status)
    {
        return auth('customer')
            ->user()
            ->onlineOrder()
            ->whereIn('order_status', $status)
            ->select(['id','invoice_number', 'order_date', 'total', 'payment_status', 'order_status'])
            ->get();
    }
}
