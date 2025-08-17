<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Warehouse\Entities\Warehouse;

class UserDetail extends Model
{
    protected $table = 'user_details';
    protected $fillable = ['user_id','warehouse_id'];
    protected $hidden = ['id','user_id','warehouse_id','created_at','updated_at'];

    /*protected static function boot(){
        parent::boot();

        static::addGlobalScope('current_warehouse',function (Builder $builder){
            $user = auth()->guard('admin')->user();
            if ($user){
                $builder->where('user_details.warehouse_id',$user->detail->warehouse->id);
            }
        });
    }*/
    /**
     * Belongs to relation with
     * @return BelongsTo
     */
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class,'users')->withoutGlobalScopes();
    }

    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
