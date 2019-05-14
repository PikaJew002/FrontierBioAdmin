<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\ProductInventoryItem as ItemResource;

class ProductFamily extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'compound_id' => $this->compound_id,
            'compound' => $this->compound,
            'short_name' => $this->short_name,
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
