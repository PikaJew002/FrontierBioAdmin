<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function contact() {
        return $this->belongsTo('App\Contact', 'contact_id', 'id');
    }

    public function items() {
        return $this->belongsToMany('App\ProductInventoryItem', 'item_order', 'order_id', 'item_id')
          ->withPivot('quantity', 'price', 'includes_msds', 'includes_cofa')
          ->withTimestamps();
    }
}
