<?php

namespace Modules\Front\Listeners;

use Modules\Front\Events\OnlineOrderRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Front\Jobs\SendAlertTelegram;

class SendNotificationToTelegram
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
        $products = $event->order->products;


        $productList = '';

        foreach ($products as $key => $product){
            $productList .= '<b><pre>' . ($key + 1) .' - '. $product->name .' - ('. $product->barcode .') - ( تعداد: '. $product->pivot->quantity .' )  </pre></b>
';
        }

        $text = '
<b>#سفارش_جدید_اف_دی_استایل </b>
<strong>لیست محصولات:</strong>
'.$productList.'
<strong>شماره سفارش: '. $event->order->invoice_number . '</strong>
<strong>مبلغ پرداختی: '. $event->order->payment->amount . 'تومان </strong>
<b>--------------------</b>
<strong>اطلاعات مشتری:</strong>
<b>'.$event->order->customer->full_name.'</b>
<b>'.$event->order->customer->mobile.'</b>
<b>'.$event->order->address->city->province->name_fa.' | '.$event->order->address->city->name_fa.' | '.$event->order->address->address.'</b>
<b>--------------------</b>
<a href="' . route('dashboard.online-order.show',$event->order->id) . '" >مشاهده جزئیات سفارش</a>
';

        SendAlertTelegram::dispatch($text);
    }
}
