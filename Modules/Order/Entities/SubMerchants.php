<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;

class SubMerchants extends Model
{
    protected $fillable = [
        'title',
        'user_id',
        'merchant_ID',
        'balance',
        'percent',
        'type',
        'status',
    ];

    public static function incrementBalance(Order $order, $gatewayFee)
    {
        $discount = $order->discount / 2;
        foreach (self::query()->get() as $merchant) {
            if ($merchant->type == "merchant") {
                $merchant->balance += ($order->subtotal - $order->total_commission - $gatewayFee - $discount) * 10 ;
            } elseif ($merchant->type == "subMerchant") {
                $sub_discount = ((($discount * $merchant->percent) / 100) * 10);
                $merchant->balance += ((($order->total_commission * $merchant->percent) / 100) * 10) - $sub_discount;
            } elseif ($merchant->type == "shipping") {
                $merchant->balance += $order->shipping * 10;
            }
            $merchant->save();
        }
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
