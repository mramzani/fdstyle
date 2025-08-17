<?php

namespace Modules\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'order',
    ];


    public function children(): HasMany
    {
        return $this->hasMany(Menu::class,'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class,'parent_id');
    }

}
