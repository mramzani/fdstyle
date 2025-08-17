<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    protected $fillable = [
        'id',
        'name_fa',
        'name_en',
    ];
}
