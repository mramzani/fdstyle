<?php

namespace Modules\Role\Entities;

use App\Services\Permission\Traits\HasPermission;
use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    use HasPermission;

    protected $fillable = ['name','display_name'];

}
