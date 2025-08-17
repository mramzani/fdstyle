<?php

namespace Modules\Order\Http\Controllers;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Payment;
use Modules\Product\Entities\StockHistory;
use Throwable;

class PurchaseController extends Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('order::purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('order::purchases.create');
    }


    /**
     * @throws Throwable
     */
    public function store()
    {
        $this->validatePurchaseStore();



        \DB::beginTransaction();
        try {
            $purchase = Order::createOrder([
                'user_id' => $this->request->input('supplier_id'),
                'discount' => $this->request->input('discount'),
                'shipping' => $this->request->input('shipping'),
                'subtotal' => $this->request->input('subtotal'),
                'total' => $this->request->input('total'),
                'paid_amount' => $this->request->input('paid_amount'),
                'due_amount' => $this->request->input('due_amount'),
                'total_items' => $this->request->input('total_items'),
                'total_quantity' => $this->request->input('total_quantity'),
            ], 'purchases');
            
            $extraData = [
                "actionType" => "add",
            ];
            Common::syncOrder($purchase, $this->request->input('products'),$extraData);

            if ($this->request->input('paid_amount') > 0
                && $this->request->input('payment_mode_id') != null) {
                $payment = Payment::createPayment([
                    'order_id' => $purchase->id,
                    'user_id' => $purchase->user_id,
                    'warehouse_id' => company()->warehouse->id,
                    'date' => Carbon::now(),
                    'amount' => $this->request->input('paid_amount'),
                    'paid_amount' => $this->request->input('paid_amount'),
                    'payment_mode_id' => $this->request->input('payment_mode_id'),
                    'reference_id' => $this->request->input('reference_id'),
                    'notes' => $this->request->input('notes'),
                ], 'out');
                $payment->payment_number = Common::generateTransactionNumber('payment-out', $payment->id);
                $payment->save();
            }
            Common::updateOrderAmount($purchase);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());

            return \Response::json([
                'status' => false,
                'message' => 'error creating purchase: ' . $exception->getMessage()
            ], 500);

        }
        $order = $purchase;
        return view('order::sales.pos-invoice', compact('order'));
    }

    public function edit(Order $purchase)
    {
        if ($purchase->payments->count() > 0 || $purchase->order_type != 'purchases') {
            return redirect()->route('dashboard.purchase.index')->with('alertWarning', 'این سفارش غیرقابل تغییر می‌باشد، سفارشات دارای پرداخت قابل ویرایش نیست');
        }
        return view('order::purchases.edit', compact('purchase'));
    }

    /**
     * @throws Throwable
     */
    public function update(Request $request, Order $purchase)
    {
        $request->validate([
            'products' => 'required',
            'products.*.quantity' => 'required',
            'products.*.unit_price' => 'required',
            'products.*.product_id' => 'required',
            'products.*.subtotal' => 'required',
            'supplier_id' => 'required',
            'subtotal' => 'required',
            'total' => 'required',
            'paid_amount' => 'required',
            'due_amount' => 'required',
            'total_items' => 'required',
            'total_quantity' => 'required',
            'discount' => 'required',
            'shipping' => 'required',
            'removed_items' => 'nullable',
        ]);
        \DB::beginTransaction();
        try {
            $removedItems = [];

            if (!is_null($request->input('removed_items'))) {
                $removedItems = json_decode($request->input('removed_items'));
            }
            $extraData = [
                "actionType" => "edit",
                "removedItems" => $removedItems,
            ];


            $purchase->update([
                'total_items' => $request->input('total_items'),
                'total_quantity' => $request->input('total_quantity'),
                'subtotal' => $request->input('subtotal'),
                'total' => $request->input('total'),
                'due_amount' => $request->input('due_amount'),
                'discount' => $request->input('discount'),
                'shipping' => $request->input('shipping'),
            ]);
            Common::syncOrder($purchase, $request->input('products'), $extraData);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning('error updating purchase with error: ' . $exception->getMessage());
            return Common::redirectTo('dashboard.purchase.index', 'alertWarning',
                'error updating purchase with error: ' . $exception->getMessage());

        }
        return Common::redirectTo('dashboard.purchase.index', 'alertSuccess', 'سفارش با موفقیت ویرایش شد.');
    }


    /**
     * @param Order $purchase
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Order $purchase)
    {
        \DB::beginTransaction();
        try {
            $orderItems = $purchase->products;

            $warehouseId = $purchase->warehouse_id;
            $orderUserId = $purchase->user_id;
            $purchase->delete();

            foreach ($orderItems as $orderItem) {

                StockHistory::create([
                    'warehouse_id' => $warehouseId,
                    'product_id' => $orderItem->pivot->product_id,
                    'variant_id' => $orderItem->pivot->variant_id,
                    'quantity' => $orderItem->pivot->quantity,
                    'old_quantity' => 0,
                    'order_type' => $purchase->order_type,
                    'stock_type' => "out",
                    'action_type' => "delete",
                    'created_by' => auth('admin')->user()->id,
                    'created_at' => Carbon::now(),
                ]);

                Common::recalculateStock($orderItem->pivot->variant_id, $warehouseId, $orderItem->pivot->product_id);
            }

            Common::updateUserAmount($orderUserId, $warehouseId,"supplier");

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('dashboard.purchase.index', 'alertWarning', 'error deleting purchase: ' . $exception->getMessage());
        }
        return Common::redirectTo('dashboard.purchase.index', 'alertSuccess', 'سفارش خرید با موفقیت حذف شد!');
    }

    private function validatePurchaseStore()
    {
        $this->request->validate([
            'products' => 'required',
            'products.*.quantity' => 'required',
            'products.*.unit_price' => 'required',
            'products.*.product_id' => 'required',
            'products.*.subtotal' => 'required',
            'supplier_id' => 'required',
            'subtotal' => 'required',
            'total' => 'required',
            'paid_amount' => 'required',
            'due_amount' => 'required',
            'total_items' => 'required',
            'total_quantity' => 'required',
            'discount' => 'required',
            'shipping' => 'required',
            'payment_mode_id' => Rule::requiredIf(function () {
                return $this->request->input('paid_amount') != 0;
            })
        ]);
    }

    public function getDetail(Request $request)
    {
        try {
            $purchase = Order::with('customer', 'payment')
                ->where('order_type', 'purchases')
                ->where('id', $request->input('sale_id'))
                ->firstOrFail();
        } catch (\RuntimeException $exception) {
            \Log::warning('error getting purchase detail: ' . $exception->getMessage());
            return \Response::json([
                'success' => false,
                'message' => 'error getting purchase detail: ' . $exception->getMessage()
            ], 500);
        }

        return view('order::purchases.purchase-detail', compact('purchase'));
    }
}
