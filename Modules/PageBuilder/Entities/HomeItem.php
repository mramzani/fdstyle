<?php

namespace Modules\PageBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class HomeItem extends Model
{
    const ITEM_TYPE = [
        'category' => 'دسته‌بندی محصول',
        'banner' => 'بنر',
        'slider' => 'اسلایدر',
        'offer' => 'کمپین فروش',
    ];
    const ROWABLE_TYPE = [
        'Modules\Category\Entities\Category' => 'دسته‌بندی‌ محصول',
        'Modules\PageBuilder\Entities\Banner' => 'بنر',
        'Modules\PageBuilder\Entities\Slider' => 'اسلایدر',
        'Modules\PageBuilder\Entities\Offers' => 'کمپین فروش',
    ];

    protected $fillable = ['home_id','priority','title'];

    public function rowable(): MorphTo
    {
        return $this->morphTo();
    }
}
