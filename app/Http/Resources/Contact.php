<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\Customer as CustomerResource;

class Contact extends Resource
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
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'prefix' => $this->prefix,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
