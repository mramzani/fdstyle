<?php

namespace Modules\PageBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    const STATUS = [
        'published' => 'انتشار یافته',
        'draft' => 'پیش نویس',
    ];
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status'
    ];

}
