<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Brand\Entities\Brand;

class SizeGuide extends Model
{
    protected $fillable = ['title','brand_id','description'];


    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

}
