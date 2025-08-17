<?php

namespace Modules\Order\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Events\PosSaleRegistered;
use Modules\User\Jobs\SendSms;

class SendSmsForCustomer
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
     * @param  PosSaleRegistered $event
     * @return void
     */
    public function handle(PosSaleRegistered $event)
    {
        $text = [
            'pattern_type' => 'thank_you_after_sale_pattern',
            'factor_link' => route('front.user.sales.show',$event->order->id)
        ];
        SendSms::dispatch($event->order->customer,$text);
    }
}
