<?php

namespace Modules\Product\Observers;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Modules\Product\Entities\StockAdjustment;
use Throwable;

class StockAdjustmentObserver
{
    /**
     * @param StockAdjustment $stockAdjustment
     * @return void
     */
    public function created(StockAdjustment $stockAdjustment)
    {
        Common::recalculateStock($stockAdjustment->variant_id,$stockAdjustment->warehouse_id,$stockAdjustment->product_id);
    }

    public function deleted(StockAdjustment $stockAdjustment)
    {
        Common::recalculateStock($stockAdjustment->variant_id,$stockAdjustment->warehouse_id,$stockAdjustment->product_id);
    }
}
