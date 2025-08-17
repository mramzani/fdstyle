<?php

namespace Modules\PageBuilder\Entities;

use App\Services\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SliderItem extends Model
{
    protected $fillable = ['slider_id','title','image','link','description','priority'];

    /*public function getImageUrlAttribute(): string
    {
        $sliderLogoPath = Common::getFolderPath('sliderImagePath');

        return $this->image == null
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($this->image, $sliderLogoPath);
    }*/

    public function getFullWithImageUrlAttribute(): string
    {
        $sliderLogoPath = Common::getFolderPath('sliderImagePath');
        $fullWithImg = explode(',',$this->image)[0];
        return $fullWithImg == null
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($fullWithImg, $sliderLogoPath);

    }

    public function getMobileImageUrlAttribute(): string
    {
        $sliderLogoPath = Common::getFolderPath('sliderImagePath');
        $mobileImage = explode(',',$this->image)[1];
        return $mobileImage == null
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($mobileImage, $sliderLogoPath);
    }


    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class,'slider_id');
    }
}
