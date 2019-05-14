<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function items() {
        return $this->hasMany('App\ProductInventoryItem');
    }

    public function family() {
        return $this->belongsTo('App\ProductFamily', 'family_id', 'id');
    }
}
