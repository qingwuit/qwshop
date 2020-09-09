<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function order_goods(){
        return $this->hasMany('App\Models\OrderGoods','order_id','id');
    }

    // 获取店铺信息
    public function store(){
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    // 获取店铺信息
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
