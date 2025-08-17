<?php

namespace Modules\Front\Adapters;


use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Front\Contracts\ResourceContract;


abstract class BaseAdapter implements ResourceContract
{
    /**
     * Resource property
     *
     */
    protected JsonResource $resource;

    abstract public static function collection($collections): array;

    /**
     * Single resource transformer
     *
     * @param JsonResource $resource
     */
    protected function __construct(JsonResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Setter for resource if you want it to be a resource
     *
     * @param JsonResource $resource
     * @return void
     */
    public function setResource($resource): void
    {
        $this->resource = $resource;
    }

    /**
     * Transform the resource into array
     *
     * @return array
     */
    public function transform(): array
    {
        return $this->resource->toArray(app('request'));
    }
}
