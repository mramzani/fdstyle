<?php

namespace Modules\Role\Entities;

use App\Services\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasRoles;
}
