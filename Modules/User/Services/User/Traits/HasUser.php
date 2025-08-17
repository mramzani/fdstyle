<?php
namespace Modules\User\Services\User\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\User\Entities\User;

trait HasUser
{
    /**
     * One user BelongsToMany Product
     * @return BelongsToMany
     */
    public function users():BelongsToMany
    {
        return $this->belongsToMany(User::class,'product_user');
    }

    public function refreshUsers(...$users)
    {
        $this->users()->sync($users);
        return $this;
    }
}
