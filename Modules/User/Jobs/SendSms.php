<?php

namespace Modules\User\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\User\Entities\Customer;
use Modules\User\Services\Notifications\Notifications;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Customer $customer;
    private array $text;


    public function __construct(Customer $customer,array $message)
    {
        $this->customer = $customer;
        $this->text = $message;
    }


    public function handle(Notifications $notifications)
    {
         return $notifications->sendSms($this->customer,$this->text);
    }
}
