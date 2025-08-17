<?php

namespace App\Services;


use App\Services\Uploader\Uploader;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Payment;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductDetail;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Entities\StockAdjustment;
use Modules\Product\Entities\StockHistory;
use Modules\User\Entities\Customer;
use Modules\User\Entities\Supplier;

class Common
{

    public Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get String and create slug
     * @param string $str
     * @return string
     */
    public static function SlugCreator(string $str): string
    {
        return Str::replace(' ', '-', rtrim($str));

    }

    public static function makeSlug($slug, $replace): string
    {
        $slug == ""
            ? $replaced = Str::replace(' ', '-', rtrim($replace))
            : $replaced = Str::replace(' ', '-', rtrim($slug));

        return $replaced;
    }

    /**
     * Get folder path and file name return file url
     * @param string $folderPath
     * @param string $fileName
     * @return string
     */
    public static function getFileUrl(string $fileName, string $folderPath): string
    {
        $path = "";
        if (config('filesystems.default') == 'local') {
            $path = 'storage' . DIRECTORY_SEPARATOR . $folderPath . DIRECTORY_SEPARATOR . $fileName;
        } elseif (config('filesystems.default') == 'liara') {
            $path = config('filesystems.disks.liara.url') . DIRECTORY_SEPARATOR . $folderPath
                . DIRECTORY_SEPARATOR . $fileName;
        }

        $path = asset($path);

        if (Str::contains($path, ',')) {
            return Str::substr($path, 0, strpos($path, ','));
        }
        return $path;
    }

    /**
     * @param string|null $type
     * @return string|string[]
     */
    public static function getFolderPath(string $type = null)
    {
        $paths = [
            'companyLogoPath' => 'companies',
            'userImagePath' => 'users',
            'expenseBillPath' => 'expenses',
            'brandImagePath' => 'brands',
            'categoryImagePath' => 'categories',
            'productImagePath' => 'products',
            'sliderImagePath' => 'sliders',
            'orderDocumentPath' => 'orders',
            'frontBannerPath' => 'banners',
            'audioFilesPath' => 'audio',
            'langImagePath' => 'langs',
            'warehouseLogoPath' => 'warehouses',
            'bannerImagePath' => 'banners',
        ];

        return ($type == null) ? $paths : $paths[$type];
    }

    /**
     * @param string $type
     * @param $number
     * @return string
     */
    public static function generateTransactionNumber(string $type, $number): string
    {
        $prefixes = [
            'payment-in' => 'PAY-IN-',
            'payment-out' => 'PAY-OUT-',
            'payment-online' => 'OnPay-IN-',
            'cancel-payment-online' => 'OnPay-OUT-',
        ];

        return $prefixes[$type] . $number;
    }

    public static function updateOrderAmount(Order $order)
    {
        self::updateOrder($order);

        $userType = "customer";
        if ($order->order_type === "purchases") {
            $userType = "supplier";
        }

        self::updateUserAmount($order->user_id, $order->warehouse_id, $userType);
    }

    private static function updateOrder(Order $order): void
    {
        $totalPaidAmount = Payment::where('order_id', $order->id)->sum('paid_amount');

        $dueAmount = $order->total - $totalPaidAmount;


        if ($dueAmount <= 0) {
            $orderPaymentStatus = 'paid';
        } elseif ($dueAmount >= $order->total) {
            $orderPaymentStatus = 'unpaid';
        } else {
            $orderPaymentStatus = 'partially_paid';
        }


        $order->due_amount = $dueAmount;
        $order->paid_amount = $totalPaidAmount;
        $order->payment_status = $orderPaymentStatus;
        $order->save();
    }


    private static function userWillPay($userId, $warehouseId)
    {
        $totalPurchaseReturnAmount = Order::sumTotalByType($userId, $warehouseId, 'purchase-returns');
        $totalSalesAmount = Order::sumTotalByType($userId, $warehouseId, 'sales');
        $totalOnlineAmount = Order::sumTotalByType($userId, $warehouseId, 'online');
        return $totalPurchaseReturnAmount + $totalSalesAmount + $totalOnlineAmount;
    }

    private static function userWillReceive($userId, $warehouseId)
    {
        $totalPurchaseAmount = Order::sumTotalByType($userId, $warehouseId, 'purchases');
        $totalSalesReturnAmount = Order::sumTotalByType($userId, $warehouseId, 'sales-returns');
        $totalOnlineCancelOrder = Order::sumCancelOrder($userId, $warehouseId);

        return $totalPurchaseAmount + $totalSalesReturnAmount;
    }

    private static function userTotalOrderAmount($userId, $warehouseId)
    {
        $userWillPay = self::userWillPay($userId, $warehouseId); //کل مبلغی که کاربر پرداخت  کرد
        $userWillReceive = self::userWillReceive($userId, $warehouseId); //کل مبلغی که کاربر دریافت کرد

        return $userWillPay - $userWillReceive;
    }

    private static function userTotalPaidPayment($userId, $warehouseId)
    {
        $totalPaidAmountByUser = Payment::sumAmountByType($userId, $warehouseId, 'in');
        $totalPaidAmountToUser = Payment::sumAmountByType($userId, $warehouseId, 'out');
        $totalOnlineCancelPaidAmountToUser = Payment::sumOnlineCancelAmount($userId, $warehouseId);

        return $totalPaidAmountByUser - $totalPaidAmountToUser - $totalOnlineCancelPaidAmountToUser;
    }

    /**
     * Try to Upload image and return image name
     * @return RedirectResponse|mixed|string
     */
    public function uploadFile()
    {
        try {
            return resolve(Uploader::class)->upload();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return $this->redirectTo('categories.index',
                'alertWarning',
                'قبلا یک فایل با این نام بارگزاری شده است.');
        }
    }

    /**
     * Try to Upload Multi image and return image name
     * @return RedirectResponse|mixed|string
     */
    public function multiUpload()
    {
        try {
            return resolve(Uploader::class)->multiUpload();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return $this->redirectTo('categories.index',
                'alertWarning',
                'قبلا یک فایل با این نام بارگزاری شده است.');
        }
    }

    public function deleteFile(string $fileName): void
    {
        resolve(Uploader::class)->delete($fileName);
    }

    /**
     * @param Request $request
     * @param $image
     * @return bool|null
     */
    public static function removeFileFromStorage(Request $request, $image): ?bool
    {
        $folder = $request->folder;

        $folderString = self::getFolderString($folder);

        $folderPath = self::getFolderPath($folderString);

        $path = $folderPath . '/' . $image;

        return \Storage::disk('public')->exists($path)
            ? \Storage::disk('public')->delete($path)
            : null;
    }

    /**
     *
     * @param string $folder
     * @return string
     */
    private static function getFolderString(string $folder): string
    {
        switch ($folder) {
            case "user":
                $folderString = "userImagePath";
                break;
            case "brand":
                $folderString = "brandImagePath";
                break;
            case "category":
                $folderString = "categoryImagePath";
                break;
            default:
                $folderString = "";
                break;
        }

        return $folderString;
    }

    /**
     * Redirect To With Message
     * @param string $to
     * @param string $with
     * @param string $message
     * @return RedirectResponse
     */
    public static function redirectTo(string $to = 'dashboard.index', string $with = 'alertInfo', string $message = 'Error Unknown'): RedirectResponse
    {
        return redirect()
            ->route($to)
            ->with($with, $message);
    }

    public static function toEnglishNumber($string)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        return str_replace($arabic, $num, $convertedPersianNums);
    }

    public static function NumericGenerator($length): int
    {
        $chars = str_shuffle('3759402687094152031368921');
        $chars = str_shuffle(str_repeat($chars, ceil($length / strlen($chars))));

        return strrev(str_shuffle(substr($chars, mt_rand(0, (strlen($chars) - $length - 1)), $length)));
    }

    public static function recalculateStock($variantId = null, $warehouseId = null, $productId = null, $isOnline = false)
    {
        $countAllOrder = self::countAllOrder($variantId, $productId, $warehouseId);

        $productDetails = ProductDetail::withoutGlobalScope('current_warehouse')
            ->where('warehouse_id', '=', $warehouseId)
            ->where('product_id', '=', $productId)
            ->first();


        if ($variantId != null) {
            $addStockAdjustment = self::sumStockAdjustment($variantId, $productId, $warehouseId, 'add');
            $subtractStockAdjustment = self::sumStockAdjustment($variantId, $productId, $warehouseId, 'subtract');

            $newStockQuantityVariant = $countAllOrder + $addStockAdjustment - $subtractStockAdjustment;

            if ($newStockQuantityVariant < 0) {
                throw new \RuntimeException('موجودی محصول ' . $productId . ' به اتمام رسیده');
            }
            $productVariant = ProductVariant::find($variantId);
            $productVariant->quantity = $newStockQuantityVariant;
            $productVariant->save();


            $productVariants = ProductVariant::where('product_id', $productId)->get();
            $totalVariantQuantity = 0;
            foreach ($productVariants as $variant) {
                $totalVariantQuantity += $variant->quantity;
            }


            if ($totalVariantQuantity <= 0) {
                $productDetails->status = 'out_of_stock';
                //self::deleteFromCart($variant);
                // اگر موجودی محصولی به اتمام رسید باید از تمام سبد های خرید حذف شود
            } else {
                $productDetails->status = 'in_stock';
            }
            $productDetails->save();

        } else {
            $addStockAdjustment = StockAdjustment::sumQuantityStockAdjustmentByType($warehouseId, $productId, 'add');
            $subtractStockAdjustment = StockAdjustment::sumQuantityStockAdjustmentByType($warehouseId, $productId, 'subtract');

            $newStockQuantity = $countAllOrder + $addStockAdjustment - $subtractStockAdjustment;


            if ($newStockQuantity < 0) {
                throw new \RuntimeException('موجودی محصول ' . $productId . ' به اتمام رسیده');
            }
            $productDetails->current_stock = $newStockQuantity;

            //update product status
            if ($productDetails->current_stock <= 0) {
                $productDetails->status = 'out_of_stock';
                // اگر موجودی محصولی به اتمام رسید باید از تمام سبد های خرید حذف شود
            } else {
                $productDetails->status = 'in_stock';
            }
            $productDetails->save();
        }

    }

    private static function countAllOrder($variantId, $productId, $warehouseId): int
    {
        $purchaseOrderCount = 0;
        $salesOrderCount = 0;
        $salesReturnsOrderCount = 0;
        $purchaseReturnsOrderCount = 0;
        $onlineOrderCount = 0;

        if ($warehouseId != null && $productId != null) {
            $salesOrderCount = self::sumOrderCount('sales', $warehouseId, $productId, $variantId);
            $purchaseOrderCount = self::sumOrderCount('purchases', $warehouseId, $productId, $variantId);
            $purchaseReturnsOrderCount = self::sumOrderCount('purchase-returns', $warehouseId, $productId, $variantId);
            $salesReturnsOrderCount = self::sumOrderCount('sales-returns', $warehouseId, $productId, $variantId);
            $onlineOrderCount = self::sumOrderCount('online', $warehouseId, $productId, $variantId);
            /*if ($isOnline) {

            }*/
        }
        return (int)($purchaseOrderCount - $salesOrderCount + $salesReturnsOrderCount - $purchaseReturnsOrderCount - $onlineOrderCount);
    }

    /**
     * @param $orderType
     * @return string
     */
    public static function generateInvoiceNumber($orderType): string
    {
        $lastOrderId = Order::max('id');
        $lastOrderId = $lastOrderId + 1;

        switch ($orderType) {
            case 'purchases':
                $invoiceNumberPrefixString = "P";
                break;
            case 'online':
                $invoiceNumberPrefixString = "ON";
                break;
            case 'sales':
                $invoiceNumberPrefixString = "S";
                break;
            case 'sales-returns':
                $invoiceNumberPrefixString = "SR";
                break;
            case 'purchase-returns':
                $invoiceNumberPrefixString = "PR";
                break;
            case 'payment-in':
                $invoiceNumberPrefixString = "PAYIN";
                break;
            case 'payment-out':
                $invoiceNumberPrefixString = "PAYOUT";
                break;
            case 'payment-online':
                $invoiceNumberPrefixString = "OnPay-IN";
                break;
            default:
                return "";
        }

        $invoiceNumber = config('order.start_order_num') + $lastOrderId;

        $orderPrefix = config('order.order_prefix');

        return $orderPrefix . $invoiceNumberPrefixString . $invoiceNumber;
    }

    public static function updateUserAmount($userId, $warehouseId, $userType = "customer")
    {
        $user = Customer::withoutGlobalScope('type')->find($userId);

        if ($userType === "supplier") {
            $user = Supplier::withoutGlobalScope('type')->find($userId);
        }

        $userDetail = $user->detail;

        $userTotalOrderAmount = self::userTotalOrderAmount($user->id, $warehouseId);

        $purchaseOrderCount = Order::countByType($user->id, $warehouseId, 'purchases');
        $purchaseReturnOrderCount = Order::countByType($user->id, $warehouseId, 'purchase-returns');
        $salesOrderCount = Order::countByType($user->id, $warehouseId, 'sales');
        $onlineOrderCount = Order::countByType($user->id, $warehouseId, 'online');
        $salesReturnOrderCount = Order::countByType($user->id, $warehouseId, 'sales-returns');

        //$userDetail->online_order_count = $onlineOrderCount;
        $userDetail->purchase_order_count = $purchaseOrderCount;
        $userDetail->purchase_return_count = $purchaseReturnOrderCount;
        $userDetail->sales_order_count = $salesOrderCount;
        $userDetail->sales_return_count = $salesReturnOrderCount;
        $userDetail->total_amount = $userTotalOrderAmount;

        $userTotalPaidPayment = self::userTotalPaidPayment($user->id, $warehouseId);


        if ($userDetail->opening_balance_type == "receive") {
            $userDetail->paid_amount = $userTotalPaidPayment - $userDetail->opening_balance;
        } else {
            $userDetail->paid_amount = $userTotalPaidPayment + $userDetail->opening_balance;
        }

        $userDetail->due_amount = $userDetail->total_amount - $userDetail->paid_amount;

        $userDetail->save();


    }

    public static function convertToJalali($input): ?string
    {
        if (!is_null($input)) {
            $date = explode('-', $input);
            $date = Verta::getGregorian($date[0], $date[1], $date[2]);
            return implode('-', $date);
        }
        return null;
    }

    public static function convertDateTimeToGregorian($dateTime): ?string
    {
        if (!is_null($dateTime)) {
            $explodedDateTime = explode(' ', $dateTime);
            $date = $explodedDateTime[0];
            $time = $explodedDateTime[1];
            return self::convertToJalali($date) . ' ' . $time;
        }
        return null;
    }

    public static function syncOnlineOrder(Order $order)
    {

        foreach ($order->products as $product) {
            self::recalculateStock($product->pivot->variant_id, $order->warehouse_id, $product->pivot->product_id, true);
        }
    }

    private static function sumStockAdjustment($variantId, $productId, $warehouseId, string $type): int
    {
        return StockAdjustment::sumQuantityStockAdjustmentByVariant($variantId, $productId, $warehouseId, $type);
    }

    public static function updateOnlineOrderAmount(Order $order)
    {
        self::updateOrder($order);

        $user = Customer::withoutGlobalScope('type')->find($order->customer->id);

        $userTotalOrderAmount = self::userTotalOrderAmount($user->id, $order->warehouse_id);

        $userTotalPaidPayment = self::userTotalPaidPayment($user->id, $order->warehouse_id);

        $userDetail = $user->detail;
        $onlineOrderCount = Order::countByType($user->id, $order->warehouse_id, 'online');
        $userDetail->online_order_count = $onlineOrderCount;
        $userDetail->total_amount = $userTotalOrderAmount;
        $userDetail->paid_amount = $userTotalPaidPayment;

        $userDetail->save();

    }

    /**
     * cancel for online order returned | cancelled
     * @param Order $order
     * @return void
     */
    public static function cancelOnlineOrder(Order $order): void
    {
        if ($order->payment_status == "paid") {
            $cancelPay = $order->payment()->create([
                'order_id' => $order->id,
                'warehouse_id' => $order->warehouse_id,
                'payment_type' => 'on-out',
                'date' => Carbon::now(),
                'amount' => $order->total,
                'paid_amount' => $order->total,
                'payment_mode_id' => 3,
                'user_id' => $order->user_id,
                'notes' => 'بابت لغو سفارش ' . $order->invoice_number . ' هزینه بازگشت داده شد.'
            ]);

            $cancelPay->payment_number = self::generateTransactionNumber('cancel-payment-online', $cancelPay->id);
            $cancelPay->save();
        }

        foreach ($order->products as $orderItem) {
            StockHistory::create([
                'warehouse_id' => $order->warehouse_id,
                'product_id' => $orderItem->pivot->product_id,
                'variant_id' => $orderItem->pivot->variant_id,
                'quantity' => $orderItem->pivot->quantity,
                'old_quantity' => 0,
                'order_type' => $order->order_type,
                'stock_type' => "in",
                'action_type' => "cancel",
                'created_by' => auth('admin')->user()->id,
                'created_at' => Carbon::now(),
            ]);
            self::recalculateStock($orderItem->pivot->variant_id, $order->warehouse_id, $orderItem->pivot->product_id);
        }
        self::updateUserAmount($order->user_id, $order->warehouse_id);
    }
    #region Refactor

    /**
     * @param $orderType
     * @param $warehouseId
     * @param $productId
     * @param $variantId
     * @return int|mixed
     */
    public static function sumOrderCount($orderType, $warehouseId, $productId, $variantId = null): int
    {
        return \DB::table('order_product')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->where('orders.warehouse_id', '=', $warehouseId)
            ->where('order_product.product_id', '=', $productId)
            ->where('order_product.variant_id', '=', $variantId)
            ->whereIn('orders.order_status', ['processing', 'preparing', 'completed', 'shipping', 'delivered'])
            ->where('orders.order_type', '=', $orderType)
            ->sum('order_product.quantity');

    }

    /**
     * @param Order $order
     * @param $productItem
     * @param array|null $data
     * @param bool $isOnline
     * @return Order
     */
    public static function syncOrder(Order $order, $productItem, array $data = null, bool $isOnline = false): Order
    {
        $actionType = "add";
        if ($data != null) {
            if ($data['actionType'] == "edit") {
                $actionType = "edit";

                foreach ($data['removedItems'] as $item) {

                    $item = (object)$item;
                    $variantId = null;

                    if ($item->variantId != '') {
                        $order->productVariant()->detach($item->variantId);
                        $variantId = $item->variantId;
                    } else {
                        $order->products()->detach($item->productId);
                    }

                    self::recalculateStock($variantId, $order->warehouse_id, $item->productId);
                }
            }
        }

        // $orderSubTotal = 0;
        //$totalQuantities = 0;
        foreach ($productItem as $item) {
            $item = (object)$item;

            if ($item->variant_id != null) {
                $order->productVariant()->sync([
                    $item->variant_id => [
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_discount' => $item->total_discount ?? 0,
                        'commission' => $item->commission ?? 0,
                        'product_id' => $item->product_id,
                        'subtotal' => $item->subtotal,
                    ]
                ], false);

                if ($order->order_type == "purchases" and $data['actionType'] != "edit") {
                    $variant = $order->productVariant()
                        ->where('id', $item->variant_id)
                        ->first();

                    $variant->update([
                        'sales_price' => $item->sales_price,
                        'purchase_price' => $item->unit_price,
                    ]);
                }

            } else {
                $order->products()->sync([
                    $item->product_id => [
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_discount' => $item->total_discount ?? 0,
                        'commission' => $item->commission ?? 0,
                        'variant_id' => $item->variant_id,
                        'subtotal' => $item->subtotal,
                    ]
                ], false);
                if ($order->order_type == "purchases") {
                    $product = Product::where('id', $item->product_id)->firstOrFail();
                    $product->detail->update([
                        'sales_price' => $item->sales_price,
                        'purchase_price' => $item->unit_price
                    ]);
                }
            }

            self::recalculateStock($item->variant_id, $order->warehouse_id, $item->product_id, $isOnline);


            StockHistory::create([
                'warehouse_id' => $order->warehouse_id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'old_quantity' => 0,
                'order_type' => $order->order_type,
                'stock_type' => $order->order_type == 'sales' || $order->order_type == 'online' || $order->order_type == 'purchase-returns' ? 'out' : 'in',
                'action_type' => $actionType,
                'created_by' => auth('admin')->check() ? auth('admin')->user()->id : auth('customer')->user()->id,
                'created_at' => Carbon::now(),
            ]);

        }


        if (!$isOnline) {
            self::updateOrderAmount($order);
        }

        return $order;
    }

    #endregion
}
