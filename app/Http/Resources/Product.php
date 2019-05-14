<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\ProductFamily as ProductFamilyResource;
use App\Http\Resources\ProductInventoryItem as ItemResource;

class Product extends Resource
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
            'family_id' => $this->family_id,
            'family' => new ProductFamilyResource($this->whenLoaded('family')),
            'type' => $this->type,
            'concentration' => $this->concentration,
            'concentration_units' => $this->concentration_units,
            'solvent' => $this->solvent,
            'amount' => $this->amount,
            'amount_units' => $this->amount_units,
            'list_price' => $this->list_price,
            'msds_pdf' => $this->msds_pdf,
            'msds_docx' => $this->msds_docx,
            'items' => ItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
