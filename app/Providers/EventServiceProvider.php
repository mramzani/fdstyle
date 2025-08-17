<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Front\Events\OnlineOrderRegistered;
use Modules\Front\Listeners\SendNotificationToTelegram;
use Modules\Front\Listeners\SendOrderStatusNotification;
use Modules\Order\Events\OrderStatusChanged;
use Modules\Order\Events\PosSaleRegistered;
use Modules\Order\Events\TrackingNumberSubmitted;
use Modules\Order\Listeners\SendSmsChangingOrderStatus;
use Modules\Order\Listeners\SendSmsForCustomer;
use Modules\Order\Listeners\SendTrackingNumberForCustomer;
use Modules\Product\Entities\StockAdjustment;
use Modules\Product\Observers\StockAdjustmentObserver;
use Modules\User\Events\LoginNewCustomer;
use Modules\User\Events\OtpCreated;
use Modules\User\Listeners\SendCouponForNewCustomer;
use Modules\User\Listeners\SendOtpForCustomer;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        /*Registered::class => [
            SendEmailVerificationNotification::class,
        ],*/

        OnlineOrderRegistered::class => [
            SendNotificationToTelegram::class,
            SendOrderStatusNotification::class,
        ],
        PosSaleRegistered::class => [
            SendSmsForCustomer::class,
        ],
        OrderStatusChanged::class => [
            SendSmsChangingOrderStatus::class,
        ],
        TrackingNumberSubmitted::class => [
            SendTrackingNumberForCustomer::class
        ],
        OtpCreated::class => [
            SendOtpForCustomer::class,
        ],
        LoginNewCustomer::class => [
            SendCouponForNewCustomer::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        StockAdjustment::observe(StockAdjustmentObserver::class);
    }
}

