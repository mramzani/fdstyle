<?php

namespace Modules\Order\Entities;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Modules\Front\Entities\Address;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductVariant;
use Modules\User\Entities\Customer;
use Modules\User\Entities\Supplier;
use Modules\User\Entities\User;
use phpDocumentor\Reflection\Types\This;

/**
 * Modules\Order\Entities\Order
 *
 * @property int $id
 * @property string $invoice_number
 * @property string $order_type
 * @property string $order_date
 * @property int|null $warehouse_id
 * @property int|null $user_id
 * @property int|null $address_id
 * @property int|null $discount
 * @property int|null $shipping
 * @property int $profit
 * @property int $total_commission
 * @property int $subtotal
 * @property int $total
 * @property int $paid_amount
 * @property int $due_amount
 * @property string $order_status
 * @property int|null $staff_user_id
 * @property string $payment_status
 * @property int $total_items
 * @property int $total_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Customer|null $customer
 * @property-read string $badge_order_status
 * @property-read string $badge_payment_status
 * @property-read string $date_time
 * @property-read \Modules\Order\Entities\Payment|null $payment
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Order\Entities\Payment[] $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductVariant[] $productVariant
 * @property-read int|null $product_variant_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read User|null $staff
 * @property-read Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDueAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStaffUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereWarehouseId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'order_type',
        'order_date',
        'warehouse_id',
        'customer_id',
        'address_id',
        'discount',
        'profit',
        'total_commission',
        'shipping',
        'subtotal',
        'total',
        'paid_amount',
        'due_amount',
        'order_status',
        'notes',
        'staff_user_id',
        'user_id',
        'payment_status',
        'total_items',
        'total_quantity',
        'shipping_method',
        'tracking_number'
    ];

    const STATUSES = [
        'paid' => 'پرداخت شده',
        'unpaid' => 'پرداخت نشده',
        'partially_paid' => 'مقداری‌پرداخت‌شده',
        'cancelled' => 'لغو شده',
        'received' => 'دریافت شده',
        'ordered' => 'سفارش داده شده',
        'completed' => 'تکمیل شده',
        'confirmed' => 'تایید شده',
        'shipping' => 'در حال ارسال',
        'delivered' => 'تحویل داده',
        'unknown' => 'نامشخص',
    ];

    const ONLINE_ORDER_STATUS = [
        'pending_payment' => 'در انتظار پرداخت',
        'processing' => 'درحال پردازش',
        'preparing' => 'درحال آماده‌سازی',
        'shipping' => 'ارسال شده',
        'delivered' => 'تحویل داده شده',
        'returned' => 'مرجوع شده',
        'cancelled' => 'لغو شده',
    ];

    const ONLINE_PAYMENT_STATUS = [
        'paid' => 'پرداخت شده',
        'unpaid' => 'پرداخت نشده',
    ];

    const SHIPPING_PROVIDER = [
        'post' => 'شرکت پست',
        'tipax' => 'شرکت تیپاکس',
        'snapp' => 'اسنپ',
        'bike' => 'پیک موتوری',
        'terminal' => 'باربری ترمینال',
    ];

    public static function sumTotalByType($userId, $warehouseId, $orderType)
    {
        return self::query()->where('user_id', $userId)
            ->where('warehouse_id', $warehouseId)
            ->where('order_type', $orderType)
            ->whereIn('order_status', ['processing', 'preparing', 'completed', 'shipping', 'delivered'])
            ->sum('total');
    }

    /**
     * @param $userId
     * @param $warehouseId
     * @param $type
     * @return int
     */
    public static function countByType($userId, $warehouseId, $type): int
    {
        return self::query()->where('user_id', '=', $userId)
            ->where('order_type', '=', $type)
            ->where('warehouse_id', '=', $warehouseId)
            ->count();
    }

    public static function sumCancelOrder($userId, $warehouseId): int
    {
        return self::query()->where('user_id', $userId)
            ->where('warehouse_id', $warehouseId)
            ->where('order_type', 'online')
            ->whereIn('order_status',['cancelled','returned'])
            ->sum('total');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }

    public function getBadgePaymentStatusAttribute(): string
    {
        switch ($this->attributes['payment_status']) {
            case 'unpaid':
                return '<span class="badge bg-label-danger me-1">' . self::STATUSES['unpaid'] . '</span>';
            case 'paid':
                return '<span class="badge bg-label-success me-1">' . self::STATUSES['paid'] . '</span>';
            case 'partially_paid':
                return '<span class="badge bg-label-warning me-1">' . self::STATUSES['partially_paid'] . '</span>';
            case 'cancelled':
                return '<span class="badge bg-label-gray me-1">' . self::STATUSES['cancelled'] . '</span>';
            default:
                return '<span class="badge bg-label-primary me-1">' . self::STATUSES['unknown'] . '</span>';
        }
    }

    public function getBadgeOrderStatusAttribute(): string
    {
        switch ($this->attributes['order_status']) {
            case 'paid':
                return '<span class="badge rounded-pill bg-label-success me-1">' . self::STATUSES['paid'] . '</span>';
            case 'unpaid':
                return '<span class="badge rounded-pill bg-label-danger me-1">' . self::STATUSES['unpaid'] . '</span>';
            case 'partially_paid':
                return '<span class="badge rounded-pill bg-label-warning me-1">' . self::STATUSES['partially_paid'] . '</span>';
            case 'cancelled':
                return '<span class="badge rounded-pill bg-label-secondary me-1">' . self::STATUSES['cancelled'] . '</span>';
            case 'received':
                return '<span class="badge rounded-pill bg-label-success me-1">' . self::STATUSES['received'] . '</span>';
            case 'ordered':
                return '<span class="badge rounded-pill bg-label-success me-1">' . self::STATUSES['ordered'] . '</span>';
            case 'completed':
                return '<span class="badge rounded-pill bg-label-success me-1">' . self::STATUSES['completed'] . '</span>';
            case 'confirmed':
                return '<span class="badge rounded-pill bg-label-info me-1">' . self::STATUSES['confirmed'] . '</span>';
            case 'shipping':
                return '<span class="badge rounded-pill bg-label-secondary me-1">' . self::STATUSES['shipping'] . '</span>';
            case 'delivered':
                return '<span class="badge rounded-pill bg-label-success me-1">' . self::STATUSES['delivered'] . '</span>';
            default:
                return '<span class="badge rounded-pill bg-label-primary me-1">' . self::STATUSES['unknown'] . '</span>';
        }
    }

    public function getOnlineOrderStatusAttribute(): string
    {
        switch ($this->attributes['order_status']) {
            case 'pending_payment':
                return '<span class="badge rounded-pill badge-warning me-1">' . self::ONLINE_ORDER_STATUS['pending_payment'] . '</span>';
            case 'processing':
                return '<span class="badge rounded-pill badge-primary me-1">' . self::ONLINE_ORDER_STATUS['processing'] . '</span>';
            case 'preparing':
                return '<span class="badge rounded-pill badge-primary me-1">' . self::ONLINE_ORDER_STATUS['preparing'] . '</span>';
            case 'shipping':
                return '<span class="badge rounded-pill badge-secondary me-1">' . self::ONLINE_ORDER_STATUS['shipping'] . '</span>';
            case 'delivered':
                return '<span class="badge rounded-pill badge-success me-1">' . self::ONLINE_ORDER_STATUS['delivered'] . '</span>';
            case 'returned':
                return '<span class="badge rounded-pill badge-warning me-1">' . self::ONLINE_ORDER_STATUS['returned'] . '</span>';
            case 'cancelled':
                return '<span class="badge rounded-pill badge-danger me-1">' . self::ONLINE_ORDER_STATUS['cancelled'] . '</span>';
            default:
                return '<span class="badge rounded-pill badge-primary me-1">' . self::STATUSES['unknown'] . '</span>';
        }
    }

    public function getOrderStatusForPanelAttribute(): string
    {
        switch ($this->attributes['order_status']) {
            case 'pending_payment':
                return '<span class="badge rounded-pill bg-label-warning me-1">' . self::ONLINE_ORDER_STATUS['pending_payment'] . '</span>';
            case 'processing':
                return '<span class="badge rounded-pill bg-label-primary me-1">' . self::ONLINE_ORDER_STATUS['processing'] . '</span>';
            case 'preparing':
                return '<span class="badge rounded-pill bg-label-secondary me-1">' . self::ONLINE_ORDER_STATUS['preparing'] . '</span>';
            case 'shipping':
                return '<span class="badge rounded-pill bg-label-secondary me-1">' . self::ONLINE_ORDER_STATUS['shipping'] . '</span>';
            case 'delivered':
                return '<span class="badge rounded-pill bg-label-warning me-1">' . self::ONLINE_ORDER_STATUS['delivered'] . '</span>';
            case 'returned':
                return '<span class="badge rounded-pill bg-label-primary me-1">' . self::ONLINE_ORDER_STATUS['returned'] . '</span>';
            case 'cancelled':
                return '<span class="badge rounded-pill bg-label-danger me-1">' . self::ONLINE_ORDER_STATUS['cancelled'] . '</span>';
            default:
                return '<span class="badge rounded-pill bg-label-primary me-1">' . self::STATUSES['unknown'] . '</span>';
        }
    }

    public function getValueOrderStatusAttribute(): string
    {
        switch ($this->attributes['order_status']) {
            case 'pending_payment':
                return self::ONLINE_ORDER_STATUS['pending_payment'];
            case 'processing':
                return self::ONLINE_ORDER_STATUS['processing'];
            case 'preparing':
                return self::ONLINE_ORDER_STATUS['preparing'];
            case 'shipping':
                return self::ONLINE_ORDER_STATUS['shipping'];
            case 'delivered':
                return self::ONLINE_ORDER_STATUS['delivered'];
            case 'returned':
                return self::ONLINE_ORDER_STATUS['returned'];
            case 'cancelled':
                return self::ONLINE_ORDER_STATUS['cancelled'];
            default:
                return self::ONLINE_ORDER_STATUS['unknown'];
        }
    }


    /**
     * @param Order $order
     * @return string
     */
    public static function getTrackingText(Order $order): string
    {
        $trackingText = "";

        if ($order->shipping_method == "post" || $order->shipping_method == "tipax") {
            $trackingText = 'کد رهگیری: ' . $order->tracking_number;
        } elseif ($order->shipping_method == "snapp" || $order->shipping_method == "bike") {
            $trackingText = 'شماره پیک: ' . $order->tracking_number;
        } else {
            $trackingText = 'شماره بارنامه: ' . $order->tracking_number;
        }

        return $trackingText;
    }


    public function getDateTimeAttribute(): string
    {
        return verta($this->attributes['order_date'])->format('j %B Y - H:i');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->using(OrderProduct::class)
            ->withPivot(['variant_id', 'quantity', 'unit_price','total_discount', 'subtotal', 'commission']);
    }

    public function productVariant(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class, 'order_product', 'order_id', 'variant_id')
            ->using(OrderProduct::class)
            ->withPivot(['variant_id', 'quantity', 'unit_price', 'subtotal']);
    }

    public static function createOrder($data, $type): Order
    {
        return self::create([
            'order_type' => $type,
            'invoice_number' => Common::generateInvoiceNumber($type),
            'order_date' => Carbon::now(),
            'warehouse_id' => company()->warehouse->id,
            'user_id' => $data['user_id'],
            'discount' => $data['discount'],
            'shipping' => $data['shipping'],
            'subtotal' => $data['subtotal'],
            'total' => $data['total'],
            'paid_amount' => $data['paid_amount'],
            'due_amount' => $data['due_amount'],
            'order_status' => "completed",
            'staff_user_id' => auth()->guard('admin')->user()->id,
            'total_items' => $data['total_items'],
            'total_quantity' => $data['total_quantity'],
        ]);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public static function cancelUnpaidOrderAfterOneHour(): void
    {
        //Log::warning('Running in time =>  ' . Carbon::now('Asia/Tehran'));

        $orders = self::query()
            ->where('order_type', 'online')
            ->where('order_status', 'pending_payment')
            ->where('payment_status', 'unpaid')->get();

        foreach ($orders as $order) {
            if (Carbon::parse($order->created_at)->diffInMinutes(new Carbon()) >= 60) {
                $order->order_status = "cancelled";
                $order->save();
                OrderHistory::new()->log($order, 'لغو خودکار سفارش به علت عدم پرداخت بعد از یک ساعت ', 'automatic');
            }
        }
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class, 'order_id')->orderBy('id', 'desc');
    }

    public static function gatewayFee(Order $order): int
    {
        $fee = ($order->total * config('front.gateway.fee'));
        if ($fee >= config('front.gateway.max_fee')) {
            $fee = config('front.gateway.max_fee');
        }
        return $fee;
    }

}
