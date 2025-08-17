<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Modules\User\Entities\Customer;

class Coupon extends Model
{
    protected $fillable = [
        "code",
        "limit",
        "percent",
        "min_basket_amount",
        "allowed_qty",
        "status",
        "expire_date",
        "couponable_id",
        "couponable_type",
    ];

    public function isExpired(): bool
    {
        return \verta($this->expire_date)->addDay()->isPast();
    }

    public function isEnable(): bool
    {
        return $this->status == 'enable';
    }

    public function couponable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getExpireDateJalaliAttribute(): string
    {
        return \verta($this->expire_date)->timezone('Asia/Tehran')->format('H:i | %d %B %Y'); // Y/n/j H:i:s
    }

    public function getCouponableTypePersianAttribute(): string
    {
        return match ($this->couponable_type) {
            "Modules\User\Entities\Customer" => __('coupon::coupons.customer'),
            "Modules\Product\Entities\Product" => __('coupon::coupons.product'),
            "Modules\Category\Entities\Category" => __('coupon::coupons.category'),
            default => __('coupon::coupons.general'),
        };
    }

    public function getStatusPersianAttribute(): string
    {
        if ($this->status == 'enable') return '<span class="badge badge-pill bg-label-success">فعال</span>';
        if ($this->status == 'disable') return '<span class="badge badge-pill bg-label-danger">غیرفعال</span>';
        return 'نامشخص';
    }

    public static function generateForNewCustomer(Customer $customer)
    {
        $coupon = self::query()->where('couponable_type', 'Modules\User\Entities\Customer')
            ->where('couponable_id', $customer->id)->first();

        if (!$coupon) {
            return $customer
                ->coupons()
                ->create([
                    'code' => coupon_setting()->coupon_prefix . Str::upper(Str::random(4)),
                    'limit' => coupon_setting()->coupon_limit,
                    'percent' => coupon_setting()->coupon_percent,
                    'min_basket_amount' => coupon_setting()->min_basket_amount,
                    'allowed_qty' => coupon_setting()->allowed_qty,
                    'expire_date' => \App\Services\Common::convertDateTimeToGregorian(verta()->addDays(coupon_setting()->deadline)),
                    'status' => 'enable',
                ]);
        }
        return $coupon;

    }

}
