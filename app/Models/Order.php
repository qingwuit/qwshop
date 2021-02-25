<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $dates = ['pay_time'];

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

    // 获取售后
    public function refund(){
        return $this->hasOne('App\Models\Refund','order_id','id');
    }

    // 获取分销日志
    public function distribution(){
        return $this->hasMany('App\Models\DistributionLog','order_id','id');
    }
}
