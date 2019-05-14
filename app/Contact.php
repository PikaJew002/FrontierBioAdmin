<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function customer() {
        return $this->belongsTo('App\Customer', 'customer_id', 'id');
    }

    public function orders() {
      return $this->hasMany('App\Order', 'contact_id', 'id');
    }
}
