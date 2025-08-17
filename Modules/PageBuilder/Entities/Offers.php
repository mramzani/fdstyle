<?php

namespace Modules\PageBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Product\Entities\Product;

class Offers extends Model
{
    const STATUS = [
        'published' => 'انتشار یافته',
        'draft' => 'پیش‌نویس',
    ];
    protected $fillable = [
        'title',
        'slug',
        'percent',
        'status',
        'start_date',
        'end_date',
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'offer_id');
    }
    public function homeItems():MorphMany
    {
        return $this->morphMany(HomeItem::class,'rowable');
    }

    public function isOfferActive(): bool
    {
        return verta()->between(verta($this->start_date),verta($this->end_date));
    }
}
