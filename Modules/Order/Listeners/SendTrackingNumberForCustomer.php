<?php

namespace Modules\Order\Listeners;

use Modules\Order\Entities\Order;
use Modules\Order\Events\TrackingNumberSubmitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Jobs\SendSms;

class SendTrackingNumberForCustomer
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
     * @param TrackingNumberSubmitted $event
     * @return void
     */
    public function handle(TrackingNumberSubmitted $event)
    {
        $text = [
            'pattern_type' => 'delivery_shipping_alert_pattern',
            'shipping_company' => Order::SHIPPING_PROVIDER[$event->order->shipping_method],
            'orderId' => $event->order->invoice_number,
            'tracking_number' => Order::getTrackingText($event->order),
        ];
        SendSms::dispatch($event->order->customer,$text);
    }
}
