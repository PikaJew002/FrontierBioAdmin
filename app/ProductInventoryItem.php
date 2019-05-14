<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductInventoryItem extends Model
{
    public function product() {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function orders() {
        return $this->belongsToMany('App\Order', 'item_order', 'item_id', 'order_id')
          ->withPivot('quantity', 'price', 'includes_msds', 'includes_cofa')
          ->withTimestamps();
    }

    public function getAll() {
        return $this->all();
    }
}
