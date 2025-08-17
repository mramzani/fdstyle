<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderProduct;
use Modules\Warehouse\Entities\Warehouse;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'variant_id',
        'value_id',
        'purchase_price',
        'sales_price',
        'quantity',
        'code',
    ];

    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(VariantValue::class, 'value_id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product', 'variant_id', 'order_id')
            ->using(OrderProduct::class)
            ->withPivot(['variant_id', 'quantity', 'unit_price', 'subtotal']);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class, 'variant_id');
    }


    public function hasStock($quantity): bool
    {
        return $this->quantity >= $quantity;
    }

}
