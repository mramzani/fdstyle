<?php

namespace App\Services\Order;

use App\Services\Cart\Facades\Cart;
use App\Services\Common;
use App\Services\Cost\CouponCost;
use App\Services\Cost\Facades\Cost;
use App\Services\Cost\ShippingCost;
use App\Services\Payment\Gateway\Contract\GatewayInterface;
use App\Services\Payment\Gateway\Zibal;
use App\Services\Payment\Transaction;
use AWS\CRT\Log;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Front\Exceptions\productIsOutOfStockException;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderHistory;
use Modules\Order\Entities\Payment;
use Modules\Product\Entities\Product;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class OrderRegister
{

    /**
     * @var ShippingCost $shippingCost
     */
    private ShippingCost $shippingCost;

    /**
     * @var Transaction $transaction
     */
    private Transaction $transaction;

    private CouponCost $coupon;

    public function __construct(ShippingCost $shippingCost, Transaction $transaction, CouponCost $coupon)
    {
        $this->shippingCost = $shippingCost;
        $this->transaction = $transaction;
        $this->coupon = $coupon;

    }

    /**
     * checkout online order
     * @throws Throwable
     */
    public function checkout(): RedirectResponse
    {
        \DB::beginTransaction();

        $isNotAvailable = $this->checkStock();

        try {
            if ($isNotAvailable['status']) {
                $product_list = implode(' | ', $isNotAvailable['products']);

                throw new productIsOutOfStockException('موجودی محصول ' . $product_list . ' به اتمام رسیده است.');
            }

            $order = $this->makeOrder();
            $products = $this->products();

            Common::syncOrder($order, $products, null, true);

            OrderHistory::new()->log($order, 'ایجاد سفارش توسط مشتری', "customer");

            $this->makePayment($order);

            Cart::instance('shopping')->clear();

            \DB::commit();

            return $this->transaction->start($order);
        } catch (productIsOutOfStockException $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return Common::redirectTo('front.cart-product.all', 'alertWarning', $exception->getMessage());
        }

    }

    /**
     * make order for online Order
     * @return Order $order
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function makeOrder(): Order
    {
        $order = new Order();
        $order->order_type = "online";
        $order->invoice_number = Common::generateInvoiceNumber('online');
        $order->order_date = Carbon::now();
        $order->warehouse_id = company()->warehouse->id;
        $order->user_id = auth('customer')->user()->id;
        $order->address_id = session()->get('default_address');
        $order->discount = $this->coupon->getCost();
        $order->shipping = $this->shippingCost->getCost();
        $order->subtotal = Cart::instance('shopping')->subTotal();
        $order->total = Cost::getTotalCosts();
        $order->paid_amount = 0;
        $order->profit = $this->calculateProfit();
        $order->total_commission = $this->calculateCommission();
        $order->due_amount = Cost::getTotalCosts();
        $order->order_status = "pending_payment";
        $order->staff_user_id = null;
        $order->total_items = Cart::instance('shopping')->totalItem();
        $order->total_quantity = Cart::instance('shopping')->itemCount();
        $order->save();

        return $order;
    }

    /**
     * get product in cart
     * @return array
     */
    private function products(): array
    {
        $products = [];
        foreach (Cart::instance('shopping')->all() as $index => $product) {

            $sale_price = $this->getPrice($product);

            if ($product->variantSelected != null and !$product->detail->isActivePromotion()) {
                $unit_price = $product->variantSelected->sales_price;
            } else {
                $unit_price = $product->detail->sales_price;
            }

            $products[$index]['quantity'] = $product->quantity;
            $products[$index]['unit_price'] = $unit_price;
            //change for promotion
            $products[$index]['total_discount'] = $product->detail->isActivePromotion()
                ? ($unit_price - $product->detail->promotion_price) * $product->quantity
                : 0;
            $products[$index]['subtotal'] = $sale_price * $product->quantity;
            $products[$index]['product_id'] = $product->id;
            $products[$index]['variant_id'] = $product->variantSelected != null
                ? $product->variantSelected->id : null;
            $products[$index]['commission'] = ($sale_price * $this->getCommission($product)) / 100;
        }
        return $products;
    }

    /**
     * make payment for online order
     * @param Order $order
     * @return void
     */
    private function makePayment(Order $order)
    {
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->warehouse_id = company()->warehouse->id;
        $payment->payment_type = "in";
        $payment->date = Carbon::now();
        $payment->amount = Cost::getTotalCosts();
        $payment->payment_mode_id = 5;
        $payment->user_id = $order->user_id;
        $payment->save();
        $payment->payment_number = Common::generateTransactionNumber('payment-online', $payment->id);
        $payment->save();
    }

    /**
     * check finally product stock
     * @return array
     */
    private function checkStock(): array
    {
        $productNotAvailable = [];

        foreach (Cart::all() as $key => $item) {
            if (!is_null($item->variantSelected)) {

                if (!$item->variantSelected->hasStock($item->quantity))
                    $productNotAvailable[$key] = $item->name . ' - ' . $item->variantSelected->option->valuable->title;
            } else {
                if (!$item->hasStock($item->quantity)) {
                    $productNotAvailable[$key] = $item->name;
                }
            }
        }

        return [
            'status' => count($productNotAvailable) > 0,
            'products' => $productNotAvailable
        ];
    }

    /**
     * calculate total commission
     * @return int
     */
    private function calculateCommission(): int
    {
        $total_commission = 0;
        foreach (Cart::instance('shopping')->all() as $product) {
            $price = $this->getPrice($product);

            $total_commission += (($price * $this->getCommission($product)) / 100) * $product->quantity;
        }

        return $total_commission;
    }

    /**
     * calculate profit
     * @return int
     */
    private function calculateProfit(): int
    {
        $profit = 0;
        foreach (Cart::instance('shopping')->all() as $product) {
            //$sales_price = $product->variantSelected != null ? $product->variantSelected->sales_price : $product->detail->sales_price;
            $sales_price = $this->getPrice($product);
            $purchase_price = $product->variantSelected != null ? $product->variantSelected->purchase_price : $product->detail->purchase_price;
            $profit += ($sales_price - $purchase_price) * $product->quantity;
        }

        return $profit - $this->calculateCommission();
    }

    private function getCommission(Product $product): int
    {

        return ($product->category != null and (int)$product->category->merchant_commission != 0) ? $product->category->merchant_commission : company()->merchant_commission;
    }

    private function getPrice(Product $product)
    {
        if ($product->variantSelected != null and !$product->detail->isActivePromotion()) {
            $price = $product->variantSelected->sales_price;
        } else {
            if ($product->detail->isActivePromotion()) {
                $price = $product->detail->promotion_price;
            } else {
                $price = $product->detail->sales_price;
            }
        }
        return $price;
    }

}
