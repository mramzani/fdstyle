<?php

namespace Modules\Order\Listeners;

use Modules\Order\Events\OrderStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Jobs\SendSms;

class SendSmsChangingOrderStatus
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
     * @param OrderStatusChanged $event
     * @return void
     */
    public function handle(OrderStatusChanged $event)
    {
        $text = [
            'pattern_type' => 'notification_order_status_pattern',
            'do' => 'تایید',
            'status' => $event->order->value_order_status,
            'orderId' => $event->order->invoice_number
        ];

        SendSms::dispatch($event->order->customer,$text);
    }
}
