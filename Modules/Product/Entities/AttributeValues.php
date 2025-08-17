<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeValues extends Model
{
    protected $fillable = ['value'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }




}
