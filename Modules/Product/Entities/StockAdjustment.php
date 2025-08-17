<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Product\Observers\StockAdjustmentObserver;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Warehouse;

class StockAdjustment extends Model
{
    protected $fillable = [
        'warehouse_id',
        'variant_id',
        'product_id',
        'quantity',
        'adjustment_type',
        'notes',
        'created_by',
    ];

    protected $table = 'stock_adjustments';

    public function getAdjustmentTypeForHumanAttribute(): string
    {
        return $this->attributes['adjustment_type'] == 'add'
            ? '<span class="badge bg-label-success">افزایش</span>'
            : '<span class="badge bg-label-warning">کاهش</span>';
    }

    public static function sumQuantityStockAdjustmentByType($warehouseId,$productId,$adjustment_type): int
    {
        return self::query()->where('warehouse_id','=',$warehouseId)
            ->where('product_id','=',$productId)
            ->where('adjustment_type','=',$adjustment_type)
            ->sum('quantity');
    }

    public static function sumQuantityStockAdjustmentByVariant($variantId, $productId,$warehouseId, string $adjustment_type): int
    {
        return self::query()
            ->where('warehouse_id','=',$warehouseId)
            ->where('product_id','=',$productId)
            ->where('variant_id','=',$variantId)
            ->where('adjustment_type','=',$adjustment_type)
            ->sum('quantity');
    }

    public function ProductVariant():BelongsTo
    {
        return $this->belongsTo(ProductVariant::class,'variant_id');
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function createdBy():BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }


}
