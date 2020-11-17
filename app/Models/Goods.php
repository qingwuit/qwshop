<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';
    protected $guarded = [];

    public function goods_class(){
        return $this->hasOne('App\Models\GoodsClass','id','class_id');
    }

    public function goods_brand(){
        return $this->hasOne('App\Models\GoodsBrand','id','brand_id');
    }

    public function goods_skus(){
        return $this->hasMany('App\Models\GoodsSku','goods_id','id');
    }

    public function goods_sku(){
        return $this->hasOne('App\Models\GoodsSku','goods_id','id');
    }
    // public function stores(){
    //     return $this->hasMany('App\Models\Store','id','store_id');
    // }
    public function store(){
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    // 获取评论数量
    public function order_comment(){
        return $this->hasMany('App\Models\OrderComment','goods_id','id');
    }

    // 订单商品
    public function order_goods(){
        return $this->hasMany('App\Models\OrderGoods','goods_id','id');
    }

    // 获取分销ID
    public function distribution(){
        return $this->hasOne('App\Models\Distribution','goods_id','id');
    }

    // 获取秒杀
    public function seckill(){
        return $this->hasOne('App\Models\Seckill','goods_id','id');
    }

    // 获取拼团
    public function collective(){
        return $this->hasOne('App\Models\Collective','goods_id','id');
    }
}
