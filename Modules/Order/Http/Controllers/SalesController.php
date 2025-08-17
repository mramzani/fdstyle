<?php

namespace Modules\Order\Http\Controllers;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\StockHistory;
use Throwable;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('order::sales.index');
    }

    public function edit(Order $sale)
    {
        if ($sale->payments->count() > 0) {
            return back()->with('alertWarning', 'این سفارش غیرقابل تغییر می‌باشد، سفارشات دارای پرداخت قابل ویرایش نیست');
        }

        return view('order::sales.edit', compact('sale'));
    }

    /**
     * @throws Throwable
     */
    public function update(Request $request, Order $sale)
    {
        $request->validate([
            'products' => 'required',
            'products.*.quantity' => 'required',
            'products.*.unit_price' => 'required',
            'products.*.product_id' => 'required',
            'products.*.subtotal' => 'required',
            'customer_id' => 'required',
            'subtotal' => 'required',
            'total' => 'required',
            'paid_amount' => 'required',
            'due_amount' => 'required',
            'total_items' => 'required',
            'total_quantity' => 'required',
            'discount' => 'required',
            'shipping' => 'required',
        ]);
        \DB::beginTransaction();
        try {
            //TODO: refactor duplicate code
            $removedItems = [];
            if (!is_null($request->input('removed_items'))) {
                $removedItems = json_decode($request->input('removed_items'));
            }

            $extraData = [
                "actionType" => "edit",
                "removedItems" => $removedItems,
            ];

            $sale->update([
                'total_items' => $request->input('total_items'),
                'total_quantity' => $request->input('total_quantity'),
                'subtotal' => $request->input('subtotal'),
                'total' => $request->input('total'),
                'due_amount' => $request->input('due_amount'),
                'discount' => $request->input('discount'),
                'shipping' => $request->input('shipping'),
            ]);

            Common::syncOrder($sale, $request->input('products'), $extraData);
            \DB::commit();
        }
        catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('error updating sale with error: ' . $exception->getMessage());
            return Common::redirectTo('dashboard.sales.index', 'alertWarning',
                'error updating sale with error: ' . $exception->getMessage());

        }
        return Common::redirectTo('dashboard.sales.index', 'alertSuccess', 'سفارش با موفقیت ویرایش شد.');
    }

    /**
     * @param Order $sale
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Order $sale)
    {
        \DB::beginTransaction();
        try {
            //TODO: Updating Warehouse History

            $orderItems = $sale->products;
            $warehouseId = $sale->warehouse_id;
            $orderUserId = $sale->user_id;
            $sale->delete();

            foreach ($orderItems as $orderItem) {

                StockHistory::create([
                    'warehouse_id' => $sale->warehouse_id,
                    'product_id' => $orderItem->pivot->product_id,
                    'variant_id' => $orderItem->pivot->variant_id,
                    'quantity' => $orderItem->pivot->quantity,
                    'old_quantity' => 0,
                    'order_type' => $sale->order_type,
                    'stock_type' => "in",
                    'action_type' => "delete",
                    'created_by' => auth('admin')->user()->id,
                    'created_at' => Carbon::now(),
                ]);
                Common::recalculateStock($orderItem->pivot->variant_id,$warehouseId,$orderItem->pivot->product_id);
            }

            Common::updateUserAmount($orderUserId,$warehouseId);

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.sales.index', 'alertWarning', 'error deleting sale: ' . $exception->getMessage());

        }

        return Common::redirectTo('dashboard.sales.index','alertSuccess','سفارش فروش با موفقیت حذف شد!');
    }

    public function getDetail(Request $request)
    {
        try {
            $sale = Order::with('customer', 'payment')
                ->where('order_type', 'sales')
                ->where('id', $request->input('sale_id'))
                ->firstOrFail();
        } catch (\RuntimeException $exception) {
            return $exception->getMessage();
        }
        //return  $sale->items;

        return view('order::sales.sale-detail', compact('sale'));
    }
}
