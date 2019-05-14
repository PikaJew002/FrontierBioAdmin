<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\ProductInventoryItem as ItemResource;
use App\Http\Resources\Contact as ContactResource;

class Order extends Resource
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
            'contact_id' => $this->contact_id,
            'contact' => new ContactResource($this->whenLoaded('contact')),
            'items' => ItemResource::collection($this->items),
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'purchase_order_number' => $this->purchase_order_number,
            'shipping_cost' => $this->shipping_cost,
            'tax' => $this->tax,
            'notes' => $this->notes,
            'fulfilled_at' => $this->fulfilled_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
