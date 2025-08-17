<?php

namespace Modules\Warehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductDetail;
use Modules\Product\Entities\ProductVariant;
use Modules\User\Entities\User;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['logo', 'name', 'phone', 'address', 'status'];


    public function getStatusPersianAttribute(): string
    {
        switch ($this->status) {
            case 'active':
                return '<span class="badge bg-label-success me-1">فعال</span>';
            case 'deActive':
                return '<span class="badge bg-label-danger me-1">غیرفعال</span>';
            default:
                return "نامشخص";
        }
    }



    public function ProductDetail(): HasMany
    {
        return $this->hasMany(ProductDetail::class);
    }


    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'stock_history');
    }


}
