<?php

namespace Modules\Category\Entities;

use App\Services\Common;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\PageBuilder\Entities\HomeItem;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\AttributeGroup;
use Modules\Product\Entities\AttributeValue;
use Modules\Product\Entities\Variant;
use Modules\Product\Entities\VariantValue;
use Modules\Product\Traits\HasProducts;
use Nestable\NestableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use NestableTrait,HasProducts;

    protected $fillable = [
        'title_fa',
        'title_en',
        'slug',
        'parent_id',
        'image',
        'variant_id',
        'attribute_group_id',
        'merchant_commission',
        'seo_title',
        'seo_description',
        'description',
    ];

    /**
     * @return string
     */
    public function getImageUrlAttribute():string
    {
        $brandLogoPath = Common::getFolderPath('categoryImagePath');

        return $this->image == null
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($this->image,$brandLogoPath);
    }

    /**
     *
     * @return HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    /**
     *
     * @return HasMany
     */
    public function child(): HasMany
    {
        return $this->subcategories()->with('child');
    }


    public function parentsCategories(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id')->with('parentsCategories');
    }


    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function value()
    {
        return $this->hasOne(VariantValue::class);
    }

    public function values()
    {
        return $this->hasMany(VariantValue::class);
    }

    public static function firstCategoryById($id)
    {
        return self::query()->where('id',$id)->firstOrFail();
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function homeItems():MorphMany
    {
        return $this->morphMany(HomeItem::class,'rowable');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class,'attribute_group_id');
    }

}
