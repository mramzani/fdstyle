<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Size extends Model
{
    protected $fillable = ['title'];

    public function VariantValue(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(VariantValue::class, 'valuable');
    }
}
