<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductAttributeValues extends Pivot
{

    public function value()
    {
        return $this->belongsTo(AttributeValues::class,'value_id','id');
    }
}
