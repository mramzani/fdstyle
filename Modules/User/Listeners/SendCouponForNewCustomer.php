<?php

namespace Modules\User\Listeners;

use Illuminate\Support\Str;
use Modules\Coupon\Entities\Coupon;
use Modules\User\Entities\Customer;
use Modules\User\Events\LoginNewCustomer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Jobs\SendSms;

class SendCouponForNewCustomer
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
     * @param LoginNewCustomer $event
     * @return void
     */
    public function handle(LoginNewCustomer $event)
    {
        //generate coupon
        if (coupon_setting()->status){
            $coupon = Coupon::generateForNewCustomer($event->customer);

            SendSms::dispatch($event->customer,[
                'coupon' => $coupon->code,
                'amount' => $coupon->limit/1000,
                'pattern_type' => 'send_coupon_for_customer',
            ]);
        }

    }

}
