<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function contacts() {
        return $this->hasMany('App\Contact', 'customer_id', 'id');
    }

    public function orders() {
      return $this->hasManyThrough('App\Order', 'App\Contact', 'customer_id', 'contact_id');
    }
}
