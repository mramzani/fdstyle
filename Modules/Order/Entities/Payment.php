<?php

namespace Modules\Order\Entities;

use App\Models\PaymentMode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\Customer;
use Modules\User\Entities\Supplier;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'warehouse_id',
        'payment_type',
        'payment_number',
        'date',
        'amount',
        'paid_amount',
        'payment_mode_id',
        'user_id',
        'reference_id',
        'notes',
    ];

    public static function sumAmountByType($userId, $warehouseId, string $type)
    {
        return self::query()
            ->where('user_id', $userId)
            ->where('warehouse_id', '=', $warehouseId)
            ->where('payment_type', $type)
            ->sum('paid_amount');
    }

    public static function sumOnlineCancelAmount($userId, $warehouseId)
    {
        return self::query()
            ->where('user_id', $userId)
            ->where('warehouse_id', '=', $warehouseId)
            ->where('payment_type', 'on-out')
            ->sum('paid_amount');
    }

    public function payMode(): BelongsTo
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'user_id');
    }

    public static function createPayment($data, $paymentType): Payment
    {
        return self::create([
            'order_id' => $data['order_id'],
            'user_id' => $data['user_id'],
            'warehouse_id' => company()->warehouse->id,
            'payment_type' => $paymentType,
            'date' => Carbon::now(),
            'amount' => $data['amount'],
            'paid_amount' => $data['paid_amount'],
            'payment_mode_id' => $data['payment_mode_id'],
            'reference_id' => $data['reference_id'],
            'notes' => $data['notes'],
        ]);
    }


    public function confirm($ref_id,$gateway,$note)
    {
        $this->paid_amount = $this->amount;
        $this->reference_id = $ref_id;
        $this->notes =  $note . ' از طریق درگاه ' . $gateway;
        $this->save();
    }
}
