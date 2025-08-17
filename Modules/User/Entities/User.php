<?php

namespace Modules\User\Entities;


use App\Services\Permission\Traits\HasPermission;
use App\Services\Permission\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\SubMerchants;
use Modules\User\Traits\Sortable;



/**
 * Modules\User\Entities\User
 *
 * @property int $id
 * @property string|null $user_type
 * @property string|null $register_type
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $mobile
 * @property string|null $password
 * @property string|null $national_code
 * @property string|null $email
 * @property string $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\User\Entities\UserDetail|null $detail
 * @property-read string $full_name
 * @property-read string $status_for_human
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Role\Entities\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Role\Entities\Role[] $roles
 * @property-read int|null $roles_count
 * @method static Builder|User dontGetFirstUser()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereMobile($value)
 * @method static Builder|User whereNationalCode($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRegisterType($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUserType($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract
{
    use HasRoles, HasPermission, Authenticatable,Sortable;

    const STATUSES = [
        'active' => 'فعال',
        'deActive' => 'غیرفعال',
    ];

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

    protected $hidden = [
        'id',
        'password',
        'updated_at',
        'register_type',
        'created_at'
    ];

    protected static function booted()
    {
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('users.user_type', '=', 'staff_members')->with('detail');
        });
        static::created(function ($user) {
            //$user->giveRolesTo('admin');
            $user->detail()->create([
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
    public function detail():HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function scopeDontGetFirstUser($query)
    {
        return $query->where('id', '!=', 1);
    }

    public function merchant(): HasOne
    {
        return $this->hasOne(SubMerchants::class,'user_id');
    }


}
