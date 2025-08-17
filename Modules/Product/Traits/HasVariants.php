<?php

namespace Modules\Product\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\ProductVariant;
use Modules\Product\Entities\Sku;
use Modules\Product\Entities\Variant;
use Modules\Product\Entities\Variation;
use Modules\Product\Exceptions\InvalidAttributeException;
use Modules\Product\Exceptions\InvalidVariantException;

trait HasVariants
{
    public function ProductVariant(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variant()
    {
        return $this->hasOne(ProductVariant::class);
    }

    /**
     * @param $variants
     * @return void
     * @throws InvalidAttributeException
     * @throws InvalidVariantException
     */
    public function addVariant($variants):void
    {

        \DB::beginTransaction();

        try {
            // if the give given variant array doesn't match the structure we want
            // it will automatically throw an InvalidVariant Exception
            // Verify if the given variant attributes already exist in the variants db

            if (in_array($this->sortAttributes($variants['productVariant']), $this->getVariants())) {
                throw new InvalidVariantException("تنوع ایجاد شده از قبل وجود دارد!", 400);
            }

            // Create the sku first, so basically you can't add new attributes to the sku

            // $sku = $this->skus->where('sku', $variant['sku'])->first();

            //$sku = $this->skus()->create($data);

            foreach ($variants['productVariant'] as $item) {

                $variant = Variant::where('type', $item['variant'])->firstOrFail();

                $value = $variant->values()->where('valuable_id', $item['value'])->firstOrFail();


                $this->ProductVariant()->create([
                    'variant_id' => $variant->id,
                    'value_id' => $value->id,
                    'code' => $variants['code'],
                    'purchase_price' => $variants['purchase_price'],
                    'sales_price' => $variants['sales_price'],
                ]);

            }

            \DB::commit();

        } catch (ModelNotFoundException $err) {
            \DB::rollBack();

            throw new InvalidAttributeException($err->getMessage(), 404);

        } catch (\Throwable $err) {
            \DB::rollBack();
            throw new InvalidVariantException($err->getMessage(), 400);
        }

        //return $this;
    }


    protected function sortAttributes($variant): array
    {
        return collect($variant)
            ->sortBy('variant')
            ->map(function ($item) {
                return [
                    'variant' => strtolower($item['variant']),
                    'value' => strtolower($item['value'])
                ];
            })
            ->values()
            ->toArray();
    }


    protected function getVariants(): array
    {
        $variants = ProductVariant::where('product_id', $this->id)->get();
        return $this->transformVariant($variants);
    }

    protected function transformVariant($variants): array
    {
        return collect($variants)
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'code' => $item->code,
                    'variant' => $item->variant->type,
                    'value' => $item->option->valuable->id
                ];
            })
            ->keyBy('id')
            ->groupBy('code')
            ->map(function ($item) {
                return collect($item)
                    ->map(function ($var) {
                        return [
                            'variant' => strtolower($var['variant']),
                            'value' => strtolower($var['value'])
                        ];
                    })
                    ->sortBy('variant')
                    ->values()
                    ->toArray();
            })
            ->all();
    }


    /**
     * Get the variations
     */
    public function getProductVariant()
    {
        return $this->ProductVariant ?? [];
    }

    public function getAvailableProductVariant()
    {
        return $this->ProductVariant->where('quantity','>',0) ?? [];
    }


}
