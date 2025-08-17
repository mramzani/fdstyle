<?php

namespace Modules\Dashboard\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guarantee extends Model
{
    protected $fillable = [
        'title',
        'description',
        'link',
        'status',
    ];



}
