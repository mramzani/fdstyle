<?php

namespace Modules\Product\Entities;

use App\Services\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Warehouse\Entities\Warehouse;

class ProductDetail extends Model
{

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'current_stock',
        'purchase_price',
        'sales_price',
        'weight',
        'preparation_time',
        'length',
        'width',
        'height',
        'tax_id',
        'stock_quantity_alert',
        'status',
        'promotion_price',
        'promotion_start_date',
        'promotion_end_date',
    ];

    protected $attributes = [
        'purchase_price' => 0,
        'sales_price' => 0,
        'current_stock' => 0,
        'stock_quantity_alert' => 0,
        'status' => 'out_of_stock',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('current_warehouse',function (Builder $builder){
            $warehouse = warehouse();
            if ($warehouse){
                $builder->where('product_details.warehouse_id',$warehouse->id);
            }
        });
    }

    /**
     *  BelongsTo relation with Product
     * @return BelongsTo
     */
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return bool
     */
    public function isActivePromotion(): bool
    {
        return \verta()->between(\verta($this->promotion_start_date),\verta($this->promotion_end_date));
    }



}
