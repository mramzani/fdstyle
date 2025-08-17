<?php

namespace Modules\Order\Http\Controllers;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Dashboard\Helper\Setting\GeneralSettings;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Payment;
use Modules\Order\Events\PosSaleRegistered;
use Throwable;

class PosController extends Controller
{

    protected Request $request;

    protected $warehouse;

    protected string $general_customer_mobile;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->warehouse = company()->warehouse;
        $this->general_customer_mobile = app(GeneralSettings::class)->general_customer_mobile;

    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('order::sales.pos');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @throws Throwable
     */
    public function store()
    {
        $this->validatePosStore();

        \DB::beginTransaction();
        try {
            $order = $this->createOrder();

            Common::syncOrder($order, $this->request->input('products'));

            if ($this->request->input('paid_amount') > 0
                    && $this->request->input('payment_mode_id') != null){
                $this->createPayment($order);
            }

            Common::updateOrderAmount($order);

            if ($order->customer->mobile != $this->general_customer_mobile){
                event(new PosSaleRegistered($order));
            }

            \DB::commit();
        }
        catch (\Exception $exception) {
            \DB::rollBack();

            \Log::warning('Error Creating Sales: ' . $exception->getMessage());

            return \Response::json([
                'status' => false,
                'message' => 'Error Creating Sales '. $exception->getMessage()
            ],500);
        }

        return view('order::sales.pos-invoice',compact('order'));
        /*return \Response::json([
            'status' => true,
            'invoice' => ,
            'message' => 'فروش جدید با موفقیت ثبت شد!',
        ]);*/
    }

    private function validatePosStore()
    {
        $this->request->validate([
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
            'payment_mode_id' => Rule::requiredIf(function (){
                return $this->request->input('paid_amount') != 0;
            })
        ]);
    }

    /**
     * @return Order
     */
    private function createOrder(): Order
    {
        $order = new Order();
        $order->order_type = "sales";
        $order->invoice_number = Common::generateInvoiceNumber('sales');
        $order->order_date = Carbon::now();
        $order->warehouse_id = $this->warehouse->id;
        $order->user_id = $this->request->input('customer_id');
        $order->discount = $this->request->input('discount');
        $order->shipping = $this->request->input('shipping');
        $order->subtotal = $this->request->input('subtotal');
        $order->total = $this->request->input('total');
        $order->paid_amount = $this->request->input('paid_amount');
        $order->due_amount = $this->request->input('due_amount');
        $order->order_status = "completed";
        $order->staff_user_id = auth()->guard('admin')->user()->id;
        $order->total_items = $this->request->input('total_items');
        $order->total_quantity = $this->request->input('total_quantity');
        $order->save();

        return $order;
    }

    private function createPayment(Order $order)
    {
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->warehouse_id = $this->warehouse->id;
        $payment->payment_type = "in";
        $payment->date = Carbon::now();
        $payment->amount = $this->request->input('paid_amount');
        $payment->paid_amount = $this->request->input('paid_amount');
        $payment->payment_mode_id = $this->request->input('payment_mode_id');
        $payment->reference_id = $this->request->input('reference_id');
        $payment->notes = $this->request->input('notes');
        $payment->user_id = $order->user_id;
        $payment->save();
        $payment->payment_number = Common::generateTransactionNumber('payment-in',$payment->id);
        $payment->save();
    }


}


