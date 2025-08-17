<?php

namespace Modules\PageBuilder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Slider extends Model
{
    protected $fillable = ['name', 'key', 'description', 'status', 'start_date', 'end_date'];

    const STATUS = [
        'published' => 'انتشار یافته',
        /*'pending' => 'معلق',*/
        'draft' => 'پیش‌نویس',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SliderItem::class, 'slider_id');
    }

    /**
     * @return string
     */
    public function getJalaliStartDateAttribute(): string
    {
        return $this->start_date != null ? verta($this->start_date)->format('j %B Y - H:i') : 'نامشخص';
    }

    /**
     * @return string
     */
    public function getJalaliEndDateAttribute(): string
    {
        return $this->end_date != null ? verta($this->end_date)->format('j %B Y - H:i') : 'نامشخص';
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
