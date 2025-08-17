<?php

namespace Modules\Front\Adapters;


use Modules\Front\Http\Resources\ProductResource;

class ProductAdapter extends BaseAdapter
{

    public function __construct($model)
    {
        parent::__construct(new ProductResource($model));
    }

    /**
     * Static function for the collection
     *
     * @param $collections
     * @return array
     */
    public static function collection($collections): array
    {
        $resource = new self($collections);

        $resource->setResource(ProductResource::collection($collections));

        return $resource->transform();
    }
}
