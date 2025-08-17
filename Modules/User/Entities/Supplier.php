<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Order\Entities\Order;
use Modules\User\Traits\Sortable;


class Supplier extends Model
{
    use Sortable;
    protected $table = 'users';
    protected $fillable = [
        'user_type',
        'first_name',
        'last_name',
        'mobile',
        'national_code',
        'password',
        'email',
        'status',
    ];

    const STATUSES = [
        'active' => 'فعال',
        'deActive' => 'غیرفعال',
    ];

    protected static function booted()
    {
        static::addGlobalScope('supplier', function (Builder $builder) {
            $builder->where('users.user_type', '=', 'suppliers')->with('detail');
        });
        static::created(function ($supplier){
            $supplier->detail()->create([
                'warehouse_id' => company()->warehouse->id,
            ]);
        });
    }


    /**
     * @return string
     *
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @param $status
     * @return string
     */
    public function getStatusForHumanAttribute($status): string
    {
        switch ($this->attributes['status']) {
            case "deActive":
                return "<span class='badge bg-label-warning'>" . trans('dashboard::common.disable') . "</span>";
            case "active":
                return "<span class='badge bg-label-success'>" . trans('dashboard::common.enable') . "</span>";
            default:
                return "<span class='badge bg-label-danger'>" . trans('dashboard::common.unknown') . "</span>";
        }
    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }
    public function purchase():HasMany
    {
        return $this->hasMany(Order::class,'user_id')->where('order_type','purchases');
    }
}
