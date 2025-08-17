<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Warehouse;

class StockHistory extends Model
{

    protected $table = "stock_histories";
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'variant_id',
        'quantity',
        'old_quantity',
        'order_type',
        'stock_type',
        'action_type',
        'created_by',
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class,'created_by');
    }

}
