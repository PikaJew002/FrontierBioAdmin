<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Product as ProductResource;

class ProductInventoryItem extends Resource
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
            'product_id' => $this->product_id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'lot_number' => $this->lot_number,
            'prep_date' => $this->prep_date,
            'exp_date' => $this->exp_date,
            'current_stock' => $this->current_stock,
            'coord1' => $this->coord1,
            'coord2' => $this->coord2,
            'coord3' => $this->coord3,
            'cofa_pdf' => $this->cofa_pdf,
            'cofa_docx' => $this->cofa_docx,
            'in_current_stock' => $this->in_current_stock,
            'previous_lot_number' => $this->previous_lot_number,
            'quantity' => $this->whenPivotLoaded('item_order', function() {
                return $this->pivot->quantity;
            }),
            'price' => $this->whenPivotLoaded('item_order', function() {
                return $this->pivot->price;
            }),
            'includes_msds' => $this->whenPivotLoaded('item_order', function() {
                return $this->pivot->includes_msds;
            }),
            'includes_cofa' => $this->whenPivotLoaded('item_order', function() {
                return $this->pivot->includes_cofa;
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
