<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function goods(){
        return $this->hasOne('App\Models\Goods','id','goods_id');
    }

    public function goods_sku(){
        return $this->hasOne('App\Models\GoodsSku','id','sku_id');
    }

    public function store(){
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    public function carts(){
        return $this->hasMany('App\Models\Cart','store_id','store_id');
    }
}
