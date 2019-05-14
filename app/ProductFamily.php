<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFamily extends Model
{
    public function products() {
        return $this->hasMany('App\Product', 'family_id', 'id');
    }

    public function items() {
        return $this->hasManyThrough('App\ProductInventoryItem', 'App\Product', 'family_id', 'product_id');
    }
}
