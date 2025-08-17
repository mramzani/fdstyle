<?php

namespace Modules\Tax\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = ['name','rate'];

    protected static function newFactory()
    {
        return \Modules\Tax\Database\factories\TaxFactory::new();
    }
}
