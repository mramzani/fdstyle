<?php

namespace Modules\Product\Http\Controllers;

use App\Services\Common;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductDetail;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Entities\StockAdjustment;
use Modules\Product\Entities\StockHistory;
use Throwable;

class StockAdjustmentController extends Controller
{
    private Request $request;
    public Collection $products;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->products = Collection::empty();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $stockAdjustments = StockAdjustment::with('product', 'warehouse')->latest()->paginate(10);
        //return $stockAdjustments;
        return view('product::adjustment.index', compact('stockAdjustments'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('product::adjustment.create');
    }


    public function search()
    {
        $validData = $this->request->validate([
            'term' => 'required|min:3',
        ]);
        $searchTerms = explode(' ', $validData['term']);

        $query = Product::query();
        foreach ($searchTerms as $searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('barcode', 'LIKE', '%' . $searchTerm . '%');
            });
        }


        $products = $query->with(['detail', 'ProductVariant'])->orderBy('id', 'DESC')->get();

        collect($products)->map(function ($product) {
            if ($product->getProductVariant()->isEmpty()) {
                $newProduct = $this->makeArrProduct($product);
                $this->products = Collection::make($this->products)->add($newProduct);
            }
            if (!$product->getProductVariant()->isEmpty()) {

                $productVariants = $product->getProductVariant();

                foreach ($productVariants as $productVariant) {
                    $newProduct = $this->makeArrProduct($productVariant);
                    $this->products->add($newProduct);
                }
            }
        })->all();

        return response()->json($this->products);
    }

    private function makeArrProduct($product): array
    {
        if ($product instanceof Product) {
            return [
                'label' => $product->name,
                'value' => $product->name,
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->barcode,
                'image' => $product->image_url,
                'sales_price' => $product->detail->sales_price ?? 0,
                'purchase_price' => $product->detail->purchase_price ?? 0,
                'current_stock' => $product->detail->current_stock ?? 0,
            ];
        } elseif ($product instanceof ProductVariant) {
            return [
                'label' => $product->product->name . ' - ' . $product->option->valuable->title,
                'id' => $product->product->id,
                'name' => $product->product->name . ' - ' . $product->option->valuable->title,
                'code' => $product->code,
                'image' => $product->product->image_url,
                'sales_price' => $product->sales_price ?? 0,
                'purchase_price' => $product->purchase_price ?? 0,
                'current_stock' => $product->quantity ?? 0,
                'variant_id' => $product->id,
            ];
        } else {
            return [];
        }
    }


    /**
     * Store a newly created resource in storage
     * @throws Throwable
     */
    public function store()
    {
        //TODO: add note for per adjustment

        $this->request->validate([
            'products' => 'required',
            'products.*.variant_id' => 'nullable',
            'products.*.product_id' => 'required',
            'products.*.quantity' => 'required',
            'products.*.action' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $warehouse = company()->warehouse;
            collect($this->request->input('products'))->each(function ($adjustment) use ($warehouse) {

                $current_stock = Product::find($adjustment['product_id'])->detail->current_stock;

                if ($adjustment['variant_id']){
                    $current_stock = ProductVariant::find($adjustment['variant_id'])->quantity;
                }

                $stockAdjustment = StockAdjustment::create([
                    'warehouse_id' => $warehouse->id,
                    'variant_id' => $adjustment['variant_id'],
                    'product_id' => $adjustment['product_id'],
                    'quantity' => $adjustment['quantity'],
                    'adjustment_type' => $adjustment['action'],
                    'created_by' => auth()->guard('admin')->user()->id,
                ]);
                $this->logStockHistory($stockAdjustment, 'add_', $current_stock);
            });
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            return Common::redirectTo('adjustments.index', 'alertWarning', ' خطا در تنظیم موجودی ' . $exception->getMessage());
        }
        return Common::redirectTo('adjustments.index', 'alertSuccess', 'تنظیم موجودی با موفقیت انجام شد!');
    }

    /**
     * Remove the specified resource from storage.
     * @param StockAdjustment $stockAdjustment
     * @return RedirectResponse
     */
    public function destroy(StockAdjustment $stockAdjustment)
    {
        $old_quantity = $stockAdjustment->product->detail->current_stock;
        $this->logStockHistory($stockAdjustment, 'delete_', $old_quantity);
        $stockAdjustment->delete();
        return back();
    }

    /**
     * @param StockAdjustment $stockAdjustment
     * @param string $actionType
     * @param int $oldQuantity
     * @return void
     */
    private function logStockHistory(StockAdjustment $stockAdjustment, string $actionType, int $oldQuantity = 0): void
    {

        $attributes = [
            'warehouse_id' => $stockAdjustment->warehouse_id,
            'product_id' => $stockAdjustment->product_id,
            'quantity' => $stockAdjustment->quantity,
            'old_quantity' => $oldQuantity,
            'order_type' => 'stock_adjustment',
            'stock_type' => $actionType == "delete_" ? ($stockAdjustment->adjustment_type == 'add' ? 'out' : 'in') : ($stockAdjustment->adjustment_type == 'add' ? 'in' : 'out'),
            'action_type' => $actionType . $stockAdjustment->adjustment_type,
            'created_by' => auth('admin')->user()->id,
            'created_at' => Carbon::now(),
        ];


        if ($stockAdjustment->variant_id == null) {
           // $stockAdjustment->product->warehouses()->attach($stockAdjustment->warehouse_id, $attributes);
            StockHistory::create($attributes);
        } else {
            $attributes['variant_id'] = $stockAdjustment->variant_id;
            StockHistory::create($attributes);
            //$stockAdjustment->ProductVariant->warehouses()->attach($stockAdjustment->warehouse_id, $attributes);

        }


    }
}
