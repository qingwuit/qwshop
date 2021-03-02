<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;
    // 店铺评论关联
    public function comments(){
        return $this->hasMany('App\Models\OrderComment','store_id','id');
    }

    // 订单
    public function orders(){
        return $this->hasMany('App\Models\Order','store_id','id');
    }

}
