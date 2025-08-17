<?php

namespace Modules\User\Listeners;

use Modules\User\Events\OtpCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\User\Jobs\SendSms;

class SendOtpForCustomer
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
     * @param OtpCreated $event
     * @return void
     */
    public function handle(OtpCreated $event)
    {
        SendSms::dispatch($event->otp->customer,[
            'code' => $event->otp->code,
            'pattern_type' => 'send_otp_for_customer',
        ]);
    }
}
