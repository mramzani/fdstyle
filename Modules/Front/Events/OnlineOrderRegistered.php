<?php

namespace Modules\Front\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Order\Entities\Order;

class OnlineOrderRegistered
{
    use SerializesModels;

    public Order $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
