<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Category\Entities\Category;

class AttributeGroup extends Model
{
    protected $fillable = ['title','category_id'];

    protected $table = 'attribute_groups';

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class,'group_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }
}
