<?php

namespace Modules\Brand\Entities;


use App\Services\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\SizeGuide;
use Modules\Product\Traits\HasProducts;

class Brand extends Model
{
    use HasFactory,HasProducts;

    protected $fillable = [
        'title_fa',
        'title_en',
        'slug',
        'image',
        'seo_title',
        'seo_description',
        'description',
    ];

    /**
     * @return string
     */
    public function getImageUrlAttribute():string
    {
        $brandLogoPath = Common::getFolderPath('brandImagePath');

        return $this->image == null
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($this->image,$brandLogoPath);
    }

    public function sizeGuide(): HasOne
    {
        return $this->hasOne(SizeGuide::class,'brand_id');
    }

}
