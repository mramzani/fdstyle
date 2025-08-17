<?php


namespace App\Services\Discount\Coupon\Traits;




use Modules\Coupon\Entities\Coupon;

trait Couponable
{
    public function coupons()
    {
        return $this->morphMany(Coupon::class, 'couponable');
    }
}
