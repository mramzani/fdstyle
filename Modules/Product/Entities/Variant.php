<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variant extends Model
{

    protected $table = 'variants';

    protected $fillable = ['title','type'];

    public function values():HasMany
    {
        return $this->hasMany(VariantValue::class,'variant_id');
    }


    /*public function products() :BelongsToMany
    {
        return $this->belongsToMany(Product::class,'variations');
    }*/



    /*public function productVariant(): HasOne
    {
        return $this->hasOne(ProductVariant::class, 'variant_id');
    }*/

    public function allowedValuesForVariant(Product $product)
    {
        return $this->values()->where('category_id',$product->category->id)->get();
    }

    public static function firstVariantByType($type,$value)
    {
        return self::query()->where($type,$value)->firstOrFail();
    }
}
