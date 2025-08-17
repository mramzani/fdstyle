<?php

namespace Modules\PageBuilder\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Home extends Model
{
    protected $fillable = [
        'name', 'start_at', 'expire_at', 'status', 'is_default'
    ];
    const STATUS = [
        'published' => 'انتشار یافته',
        'draft' => 'پیش‌نویس',
    ];
    const ROWABLE_TYPE = [
        'category' => 'Modules\Category\Entities\Category',
        'banner' => 'Modules\PageBuilder\Entities\Banner',
        'slider' => 'Modules\PageBuilder\Entities\Slider',
        'offer' => 'Modules\PageBuilder\Entities\Offers',
    ];


    public static function resetDefaultHome()
    {
        foreach (self::all() as $item) {
            $item->is_default = false;
            $item->save();
        }

    }

    public function items(): HasMany
    {
        return $this->hasMany(HomeItem::class, 'home_id');
    }

    /**
     * @return Model|HasMany|object|null
     */
    public function itemSlider()
    {
        return $this->hasMany(HomeItem::class, 'home_id')
            ->where('rowable_type', self::ROWABLE_TYPE['slider'])
            ->orderBy('priority', 'desc')
            ->first();
    }

    public function isShowTime(): bool
    {
        return verta()->between(verta($this->start_at), verta($this->expire_at));
    }

    public function scopeIsPublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getBadgeStatusAttribute(): string
    {
        switch ($this->status) {
            case 'published':
                return '<span class="badge bg-primary me-1">' . self::STATUS['published'] . '</span>';
            case 'draft':
                return '<span class="badge bg-secondary me-1">' . self::STATUS['draft'] . '</span>';
            default:
                return '<span class="badge bg-primary me-1">نامشخص</span>';
        }
    }

    public static function whichHomeToDisplay()
    {
        $homeToDisplay = self::query()->where('is_default',true)->first();

        foreach (self::query()
                     ->where('status', 'published')
                     ->where('is_default',false)
                     ->get() as $home) {
            if ($home->isShowTime()) {
                return $home;
           }
        }
        return $homeToDisplay;
    }
}
