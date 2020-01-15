<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeckillGoods extends Model
{
    protected $table = "seckill_goods"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    // 获取商品信息
    public function goods(){
        return $this->hasOne("App\Models\Goods",'id','goods_id');
    }

    // 规格价格
    public function spec_once(){
        return $this->hasOne('App\Models\GoodsSpec','goods_id','goods_id');
    }
}
