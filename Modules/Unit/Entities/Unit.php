<?php

namespace Modules\Unit\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'operator',
        'operator_value',
        'is_deletable',
    ];

    protected $hidden = [
        'base_unit',
        'parent_id',
        'operator',
        'operator_value',
        'is_deletable',
        'created_at',
        'updated_at',
    ];

}
