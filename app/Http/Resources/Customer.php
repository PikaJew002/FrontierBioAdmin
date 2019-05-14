<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Contact as ContactResource;
use App\Http\Resources\Order as OrderResource;

class Customer extends Resource
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
          'name' => $this->name,
          'short_name' => $this->short_name,
          'number' => $this->number,
          'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
          'orders' => OrderResource::collection($this->whenLoaded('orders')),
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at
      ];
    }
}
