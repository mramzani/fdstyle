<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Category\Entities\Category;

class VariantValue extends Model
{
    protected $fillable = ['variant_id','category_id'];

    protected $table = 'variant_values';

    const COLOR = "color";
    const SIZE = "size";

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function ProductVariant(): HasMany
    {
        return $this->hasMany(ProductVariant::class,'value_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public static function findValuableType($variantValue,$variant_type)
    {
        if ($variant_type == self::COLOR){
            return Color::where('id',$variantValue)->firstOrFail();
        } elseif ($variant_type == self::SIZE){
            return Size::where('id',$variantValue)->firstOrFail();
        } else {
            return null;
        }
    }

    public function valuable():MorphTo
    {
        return $this->morphTo();
    }
}
