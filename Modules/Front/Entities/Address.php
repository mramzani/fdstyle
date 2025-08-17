<?php

namespace Modules\Front\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;
use Modules\Dashboard\Entities\City;
use Modules\User\Entities\Customer;

class Address extends Model
{
    protected $fillable = ['user_id', 'city_id', 'transferee', 'mobile', 'address', 'plaque', 'unit', 'postal_code', 'is_default'];

    protected static function booted()
    {
        static::creating(function () {
            self::resetDefaultAddress();
        });
    }


    /**
     * make customer full address
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        return $this->city->province->name_fa . ' | ' . $this->city->name_fa . ' | ' . $this->attributes['address'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public static function resetDefaultAddress()
    {
        foreach (self::all() as $item) {
            $item->is_default = false;
            $item->save();
        }
    }


}
