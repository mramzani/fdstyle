<?php

namespace Modules\User\Services\User;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Warehouse;

class UserPivot extends Pivot
{

    public function user()
    {
        return $this->belongsTo(
            User::class, 'user_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(
            Warehouse::class, 'user_id', 'id');
    }
}
