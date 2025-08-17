<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;

class OrderHistory extends Model
{
    protected $table = 'order_histories';

    protected $fillable = [
        'order_id',
        'action',
        'last_status',
        'action_by',
    ];

    public static function new(): OrderHistory
    {
        return new self();
    }

    public function log(Order $order,$action,$type = "admin")
    {
        $this->order_id = $order->id;
        $this->action = $action;
        $this->last_status = $order->order_status;
        $this->action_by = $type == "admin" ? auth('admin')->user()->id : null;
        $this->save();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class,'action_by');
    }

    public function getTimelineColorAttribute()
    {
        switch ($this->attributes['last_status']){
            case 'pending_payment':
                return 'warning';
            case 'processing':
                return 'info';
            case 'preparing':
                return 'secondary';
            case 'shipping':
                return 'gray';
            case 'delivered':
                return 'success';
            case 'returned':
                return 'dark';
            case 'cancelled':
                return 'danger';
        }
    }
}






