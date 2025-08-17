<?php

namespace Modules\Product\Traits;


use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Entities\Attribute;


trait HasAttributes
{

//    /**
//     * @return HasMany
//     */
//    public function attributes():HasMany
//    {
//        return $this->hasMany(Attribute::class);
//    }
//
//
//    /**
//     * @param string $attribute
//     * @return $this
//     */
//    public function addAttribute(string $attribute)
//    {
//        \DB::beginTransaction();
//        try {
//            $this->attributes()->create(['name' => $attribute]);
//
//            \DB::commit();
//        }catch (\Throwable $err){
//            \DB::rollBack();
//            throw new \InvalidArgumentException($err->getMessage(), 422);
//        }
//        return $this;
//    }
//
//
//    /**
//     * @param array $attribute
//     * @return $this
//     */
//    public function addAttributes(array $attribute)
//    {
//        \DB::beginTransaction();
//        try {
//            $this->attributes()->createMany($attribute);
//            \DB::commit();
//        }catch (\Throwable $err){
//            \DB::rollBack();
//            throw new \InvalidArgumentException($err->getMessage(), 422);
//        }
//        return $this;
//    }
//
//    public function hasAttribute($key): bool
//    {
//        // If the arg is a numeric use the id else use the name
//        if (is_numeric($key)) {
//            return $this->attributes()->where('id', $key)->exists();
//        } elseif (is_string($key)) {
//            return $this->attributes()->where('name', $key)->exists();
//        }
//
//        return false;
//    }
//
//    public function addAttributeTerm(string $option, $value)
//    {
//        $attribute = $this->attributes()->where('name', $option)->first();
//
//        if (! $attribute) {
//            throw new \InvalidArgumentException("Invalid attribute", 422);
//        }
//
//        return $attribute->addValue($value);
//    }
//
//
//    /**
//     * @param $attr
//     * @return $this
//     */
//    public function removeAttribute($attr)
//    {
//        \DB::beginTransaction();
//
//        try {
//            $attribute = $this->attributes()->where('name', $attr)->firstOrFail();
//
//            $attribute->delete();
//
//            \DB::commit();
//        } catch (\Throwable $err) { // No matter what error will occur we should throw invalidAttribute
//            \DB::rollBack();
//
//            throw new \InvalidArgumentException($err->getMessage(), 422);
//        }
//
//        return $this;
//    }
//
//    public function loadAttributes()
//    {
//        return $this->attributes()->get()->load('values');
//    }
}
