<?php

namespace Modules\PageBuilder\Entities;

use App\Services\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Banner extends Model
{
    protected $fillable = [
        'name',
        'key',
        'type',
        'banner_1',
        'banner_2',
        'banner_3',
        'banner_4',
        'status',
    ];

    const STATUS = [
        'published' => 'انتشار یافته',
        /*'pending' => 'معلق',*/
        'draft' => 'پیش‌نویس',
    ];
    const BANNER_TYPE = [
        'banner1x' => 'بنرتکی',
        'banner2x' => 'بنردوتایی',
        'banner4x' => 'بنرچهارتایی',
    ];

    private function imageUrl($name): string
    {
        $sliderLogoPath = Common::getFolderPath('bannerImagePath');

        return $name == ""
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($name, $sliderLogoPath);
    }

    public function getBanner1ImgUrlAttribute(): string
    {
        return $this->banner_1 == null ? '' : $this->imageUrl(unserialize($this->banner_1)['img_url']);
    }

    public function getBanner1LinkAttribute(): string
    {
        return $this->banner_1 == null ? '' : unserialize($this->banner_1)['link'];
    }

    public function getBanner2ImgUrlAttribute(): string
    {
        return $this->banner_2 == null ? '' : $this->imageUrl(unserialize($this->banner_2)['img_url']);
    }

    public function getBanner2LinkAttribute(): string
    {
        return $this->banner_2 == null ? '' : unserialize($this->banner_2)['link'];
    }

    public function getBanner3ImgUrlAttribute(): string
    {
        return $this->banner_3 == null ? '' : $this->imageUrl(unserialize($this->banner_3)['img_url']);
    }

    public function getBanner3LinkAttribute(): string
    {
        return $this->banner_3 == null ? '' : unserialize($this->banner_3)['link'];
    }

    public function getBanner4ImgUrlAttribute(): string
    {
        return $this->banner_4 == null ? '' : $this->imageUrl(unserialize($this->banner_4)['img_url']);
    }

    public function getBanner4LinkAttribute(): string
    {
        return $this->banner_4 == null ? '' : unserialize($this->banner_4)['link'];
    }

    /**
     * @return string
     */
    public function getBadgeStatusAttribute(): string
    {
        switch ($this->status) {
            case 'published':
                return '<span class="badge bg-primary me-1">' . self::STATUS['published'] . '</span>';
            case 'pending':
                return '<span class="badge bg-warning me-1">' . self::STATUS['pending'] . '</span>';
            case 'draft':
                return '<span class="badge bg-secondary me-1">' . self::STATUS['draft'] . '</span>';
            default:
                return '<span class="badge bg-primary me-1">نامشخص</span>';
        }
    }

    public function homeItems():MorphMany
    {
        return $this->morphMany(HomeItem::class,'rowable');
    }
}

