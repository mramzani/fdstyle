<?php

namespace Modules\Front\Listeners;

use Modules\Front\Events\OnlineOrderRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Entities\Order;
use Modules\User\Jobs\SendSms;

class SendOrderStatusNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OnlineOrderRegistered $event
     * @return void
     */
    public function handle(OnlineOrderRegistered $event)
    {
        $text = [
            'pattern_type' => 'notification_order_status_pattern',
            'do' => 'دریافت',
            'status' => Order::ONLINE_ORDER_STATUS[$event->order->order_status],
            'orderId' => $event->order->invoice_number
        ];
        SendSms::dispatch($event->order->customer,$text);
    }
}
