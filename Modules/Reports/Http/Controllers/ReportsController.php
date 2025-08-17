<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Payment;
use Modules\Product\Entities\Product;

class ReportsController extends Controller
{

    public function profitLoss()
    {
        $warehouse = warehouse();
        //$request = request();
        $sales = Order::where('order_type', 'sales');
        $purchases = Order::where('order_type', 'purchases');
        $online = Order::where('order_type', 'online');

        $paymentReceived = Payment::where('payment_type', 'in');
        $paymentSent = Payment::where('payment_type', 'out');

        $sales = $sales->where('orders.warehouse_id', $warehouse->id);
        $purchases = $purchases->where('orders.warehouse_id', $warehouse->id);
        $online = $online->where('orders.warehouse_id', $warehouse->id);

        $paymentReceived = $paymentReceived->where('payments.warehouse_id', $warehouse->id);
        $paymentSent = $paymentSent->where('payments.warehouse_id', $warehouse->id);

        $sales = $sales->sum('total');
        $purchases = $purchases->sum('total');

        $online_profit = $online->where('payment_status','paid')->sum('profit');
        //$total_online = $online->sum('total');

        $paymentReceived = $paymentReceived->sum('amount');
        $paymentSent = $paymentSent->sum('amount');

        $product_purchase_value = $this->calculateProductValues('purchase_price');
        $product_sales_value = $this->calculateProductValues('sales_price');

        $total_profit = $sales + $online_profit - $product_purchase_value ;

        //$profitByPayment = $paymentReceived - $paymentSent;

        return view('reports::reports.profit-loss',compact('purchases',
            'sales','online_profit','product_purchase_value','product_sales_value','total_profit'));

    }

    private function calculateProductValues(string $price_type): int
    {
        $products = Product::query()
            ->whereHas('detail', function (Builder $builder) {
                $builder->where('status', 'in_stock');
            })->get();

        $total = 0;

        foreach ($products as $product) {
            if (count($product->ProductVariant) > 0) {
                foreach ($product->ProductVariant as $variant) {
                    $total += $variant->quantity * $variant->$price_type;
                }
            } else {
                $total += $product->detail->$price_type * $product->detail->current_stock;
            }

        }

        return $total;
    }
}
