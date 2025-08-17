<?php

namespace Modules\User\Entities;


use App\Services\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Coupon\Entities\Coupon;
use Modules\Front\Entities\Address;
use Modules\Front\Entities\Cart;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\Role\Entities\Role;
use Modules\User\Database\factories\CustomerFactoryFactory;
use Modules\User\Services\User\UserPivot;
use Modules\User\Traits\Sortable;
use Modules\Warehouse\Entities\Warehouse;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{

    use Sortable, HasRoles, HasFactory;

    /**
     * @param ...$parameters
     * @return CustomerFactoryFactory
     */
    public static function factory(...$parameters): CustomerFactoryFactory
    {
        return CustomerFactoryFactory::new();
    }

    protected $fillable = [
        'user_type',
        'register_type',
        'first_name',
        'last_name',
        'mobile',
        'national_code',
        'password',
        'email',
        'status',
    ];

    protected $hidden = [
        'warehouse_id',
        'password',
        'updated_at',
        'register_type',
        'created_at'
    ];

    protected $table = 'users';

    public static function new(): Customer
    {
        return new self();
    }

    protected static function booted()
    {
        static::addGlobalScope('customer', function (Builder $builder) {
            $builder->where('users.user_type', '=', 'customer')->with('detail');
        });

        static::created(function ($customer) {
            //$customer->giveRolesTo('customer');
            $customer->detail()->create([
                'user_id' => $customer->id,
                'warehouse_id' => company()->warehouse->id, //TODO: refactor
            ]);
        });

    }

    /**
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id');
    }

    /**
     * @return string
     *
     */
    public function getFullNameAttribute(): string
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * @param $status
     * @return string
     */
    public function getStatusForHumanAttribute($status): string
    {
        switch ($this->attributes['status']) {
            case "deActive":
                return "<span class='badge bg-label-warning'>غیرفعال</span>";
            case "active":
                return "<span class='badge bg-label-success'>فعال</span>";
            default:
                return "<span class='badge bg-label-danger'>نامشخص</span>";
        }
    }

    public function getRegisterTypeAttribute()
    {
        switch ($this->attributes['register_type']) {
            case "dashboard":
                return '<span class="badge bg-label-danger me-1">داشبورد</span>';
            case "online_shop":
                return '<span class="badge bg-label-success me-1">فروشگاه آنلاین</span>';
            default:
                 return '<span class="badge bg-label-warning me-1">نامشخص</span>';
        }
    }

    /**
     * One User Has Many warehouse
     * @return HasMany
     */
    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    /**
     *  One User belongs To Many Product
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class, 'product_user')
            ->using(UserPivot::class)
            ->withPivot(['product_id', 'user_id']);
    }

    public function sale(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id')->where('order_type', 'sales');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'identifier');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'user_id');
    }


    /**
     * @return HasMany
     */
    public function onlineOrder(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id')->where('order_type', 'online');
    }


    /**
     * @param string $mobile
     * @return bool
     */
    public static function existsCustomer(string $mobile): bool
    {
        return self::withoutGlobalScope('customer')
            ->where('mobile', $mobile)->exists();
    }

    public function coupons(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Coupon::class,'couponable');
    }

}
