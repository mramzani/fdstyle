<?php

namespace Modules\Front\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Entities\Product;


/**
 * Modules\Front\Http\Resources\ProductResource
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title_fa" => $this->name,
            "images" => $this->getImageProduct(),
            "category" => $this->category != null ?
                [
                    "id" => $this->category->id,
                    "title_fa" => $this->category->title_fa,
                    "title_en" => $this->category->title_en,
                    "slug" => $this->category->slug,
                ] : null,
            "brand" => $this->brand != null ?
                [
                    "id" => $this->brand->id,
                    "title_fa" => $this->brand->title_fa,
                    "title_en" => $this->brand->title_en,
                    "slug" => $this->brand->slug,
                ] : null,
            "default_variant" => $this->getDefaultVariant(),
            "attributes" => $this->getProductAttributes(),
            "variants" => $this->getProductVariantArray(),
            "preparation_time" => $this->detail->preparation_time,

        ];
    }

    private function getProductVariantArray(): array
    {
        if ($this->hasVariantsProduct() != null) {
            $variant = [];
            $variant_list = [];

            foreach ($this->getProductVariant()->where('quantity','>',0) as $index => $productVariant) {
                $variant_list[$index]["id"] = $productVariant->id;
                $variant_list[$index]["title"] = $productVariant->option->valuable->title;
                $variant_list[$index]["code"] = $productVariant->option->valuable->code;
                $variant_list[$index]["price"] = $productVariant->sales_price;
                $variant_list[$index]["quantity"] = $productVariant->quantity;
            }
            if (count($variant_list) > 0) {
                $variant = [
                    "type" => $this->category->variant->type,
                    "title" => $this->category->variant->title,
                ];
                $variant["list"] = $variant_list;
            }

            return $variant;
        }
        return [];
    }

    private function getImageProduct(): array
    {
        if ($this->image != null) {
            $image_list = [];
            if (count($this->all_image) === 1) {
                return [
                    "main" => [
                        "url" => $this->getImageUrl($this->all_image[0])
                    ],
                    "list" => []];
            } else {
                $main = ["url" => $this->getImageUrl($this->all_image[0])];
                foreach ($this->all_image as $index => $image) {
                    $image_list[$index]["url"] = $this->getImageUrl($this->all_image[$index]);
                }
            }
            return [
                "main" => $main,
                "list" => $image_list,
            ];

        }
        return [
            "main" => [
                "url" => $this->image_url
            ],
            "list" => []
        ];

    }

    private function getDefaultVariant()
    {
        if ($this->ProductVariant()->exists()) {
            return $this->sortPriceDesc() != null ?
              [
                "variant_id" => $this->sortPriceDesc()['variant_id'],
                "product_id" => $this->id,
                "seller" => [
                    "title" => get_short_name()
                ],
                "warranty" => [
                    "title_fa" => $this->guarantee_id != null ? $this->guarantee->title : get_default_guarantee()->title,
                    "description" => $this->guarantee_id != null ? $this->guarantee->description : get_default_guarantee()->description,
                    "link" => $this->guarantee_id != null ? $this->guarantee->link : get_default_guarantee()->link,
                ],
                "selling_price" =>  $this->sortPriceDesc()['selling_price'],
                "stock" => $this->sortPriceDesc()['stock'],
                $this->category->variant->type => $this->sortPriceDesc()[$this->category->variant->type],
                "isPromotion" => $this->detail->isActivePromotion(),
                "promotion_price" => $this->detail->promotion_price,
            ]
                : [];

        } else {
            return $this->detail->current_stock != 0 ? [
                "product_id" => $this->id,
                "variant_id" => null,
                "seller" => [
                    "title" => get_short_name()
                ],
                "warranty" => [
                    "title_fa" => $this->guarantee_id != null ? $this->guarantee->title : get_default_guarantee()->title,
                    "description" => $this->guarantee_id != null ? $this->guarantee->description : get_default_guarantee()->description,
                    "link" => $this->guarantee_id != null ? $this->guarantee->link : get_default_guarantee()->link,
                ],
                "selling_price" => $this->detail->sales_price,
                "stock" => $this->detail->current_stock,
                "isPromotion" => $this->detail->isActivePromotion(),
                "promotion_price" => $this->detail->promotion_price,
            ] : [];
        }

    }

    private function sortPriceDesc()
    {
        return $this->ProductVariant
            ->collect()
            ->sortBy('sales_price')
            ->where('quantity','>',0)
            ->map(function ($item) {
                return [
                    "variant_id" => $item['id'],
                    "selling_price" => $item['sales_price'],
                    "stock" => $item['quantity'],
                    $item->variant->type => [
                        "id" => $item->option->valuable->id,
                        "title" => $item->option->valuable->title,
                        "code" => $item->option->valuable->code,
                    ]
                ];
            })
            ->values()
            ->first();
    }

    private function getProductAttributes()
    {
        return $this->attributes
        ->collect()->map(function ($attribute) {
            return [
                "name" => $attribute->name,
                "value" => $attribute->pivot->value->value
            ];
        })->toArray();
    }
}
