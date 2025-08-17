<?php

namespace Modules\Product\Entities;

use App\Services\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\URL;
use Modules\Brand\Entities\Brand;
use Modules\Category\Entities\Category;
use Modules\Dashboard\Entities\Guarantee;
use Modules\Order\Entities\Order;
use Modules\PageBuilder\Entities\Offers;
use Modules\Order\Entities\OrderProduct;
use Modules\Product\Database\factories\ProductFactory;
use Modules\Product\Traits\HasVariants;
use Modules\Unit\Entities\Unit;
use Modules\User\Services\User\Traits\HasUser;


class Product extends Model
{

    use HasUser, HasVariants, HasFactory;

    public static function factory(...$parameters): ProductFactory
    {
        return ProductFactory::new();
    }
    /*protected $connection = "mysql";
    protected $table = "products";*/

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'barcode',
        'image',
        'row',
        'floor',
        'guarantee_id',
        'category_id',
        'brand_id',
        'unit_id',
        'description',
        'offer_id',
    ];


    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_product', 'product_id', 'attribute_id')
            ->using(ProductAttributeValues::class)
            ->withPivot(['value_id', 'product_id', 'attribute_id']);
    }

    /**
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        $productLogoPath = $this->getProductFolderPath();

        return $this->image == null
            ? asset('assets/panel/img/no-image-available.jpg')
            : Common::getFileUrl($this->image, $productLogoPath);
    }

    public function getAllImageAttribute(): array
    {
        return $this->image == null ? [] : explode(",", $this->image);
    }

    public function getPageUrlAttribute(): string
    {
        return $this->attributes['page_url'] = URL::route('front.product.show', ['product' => $this->id, 'name' => $this->slug]);
    }

    public function getImageUrl($img): string
    {
        $productLogoPath = $this->getProductFolderPath();
        return Common::getFileUrl($img, $productLogoPath);
    }

    private function getProductFolderPath()
    {
        return Common::getFolderPath('productImagePath');
    }

    /**
     * one product has one product_detail
     * @return HasOne
     */
    public function detail(): HasOne
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function setBarcodeAttribute($value)
    {
        $this->attributes['barcode'] = Common::toEnglishNumber($value);
    }

    public static function withoutImage(): int
    {
        return self::query()->where('image', "=", null)->count();
    }

    public static function withoutBrand(): int
    {
        return self::query()->where('brand_id', "=", null)->count();
    }

    public static function withoutCategory(): int
    {
        return self::query()->where('category_id', "=", null)->count();
    }

    public static function stockOut(): int
    {
        return self::query()->whereHas('detail', function ($q) {
            $q->where('current_stock', '<=', 0);
        })->count();
    }

    public function scopeAvailable($query)
    {
        return $query->whereHas('detail', function ($q) {
            $q->where('status', 'in_stock');
        });
    }

    public function hasStock(int $quantity): bool
    {
        return $this->detail->current_stock >= $quantity;
    }

    public function scopeWithImage(Builder $query, bool $show = false): Builder
    {
        if (!$show) {
            return $query->whereNotNull('image');
        }
        return $query;
    }

    /**
     * Create Slug for Product
     * @param $value
     * @return string
     */
    public function setSlugAttribute($value): string
    {
        return $this->attributes['slug'] = $value ? Common::SlugCreator($value) : Common::SlugCreator($this->attributes['name']);
    }

    public function hasVariantsProduct()
    {
        return $this->category->variant ?? [];
    }

    /**
     * check exists variant by code
     * @param string $code
     * @return bool
     */
    public function existsVariantByCode(string $code): bool
    {
        return $this->ProductVariant()->where('code', $code)->exists();
    }

    /**
     * check exists variant by product_id
     * @return bool
     */
    public function existsVariantId(): bool
    {
        return $this->ProductVariant()->where('product_id', $this->id)->exists();
    }

    public function notAllowedAddProduct(string $code): bool
    {
        return !$this->existsVariantByCode($code) and $this->existsVariantId();
    }

    public static function ProductJoinVariant($code)
    {
        return self::query()->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->select(['products.id', 'products.name', 'product_variants.id as product_variant_id',
                'product_variants.code', 'product_variants.sales_price', 'product_variants.quantity'])
            ->where('product_variants.code', '=', $code)
            ->first();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->using(OrderProduct::class)
            ->withPivot(['variant_id', 'quantity', 'unit_price', 'total_discount', 'subtotal']);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class, 'product_id');
    }

    public function hasStockWithVariant(): bool
    {
        return $this->detail->current_stock > 0 or $this->ProductVariant()->where('quantity', '>', 0)->exists();
    }

    public function guarantee()
    {
        return $this->belongsTo(Guarantee::class);
    }
    
    public function offer()
    {
        return $this->belongsTo(Offers::class,'offer_id');
    }
}
