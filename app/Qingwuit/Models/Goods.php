<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goods extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function goods_class()
    {
        return $this->hasOne('App\Qingwuit\Models\GoodsClass', 'id', 'class_id')->withTrashed();
    }

    public function goods_brand()
    {
        return $this->hasOne('App\Qingwuit\Models\GoodsBrand', 'id', 'brand_id')->withTrashed();
    }

    public function goods_skus()
    {
        return $this->hasMany('App\Qingwuit\Models\GoodsSku', 'goods_id', 'id');
    }

    public function goods_sku()
    {
        return $this->hasOne('App\Qingwuit\Models\GoodsSku', 'goods_id', 'id');
    }

    public function order_comment()
    {
        return $this->hasMany('App\Qingwuit\Models\OrderComment', 'goods_id', 'id');
    }

    public function order_goods()
    {
        return $this->hasMany('App\Qingwuit\Models\OrderGoods', 'goods_id', 'id');
    }

    public function store()
    {
        return $this->hasOne('App\Qingwuit\Models\Store', 'id', 'store_id')->withTrashed();
    }

    public function seckill()
    {
        return $this->hasOne('App\Qingwuit\Models\Seckill', 'goods_id', 'id')->withTrashed();
    }

    // 获取拼团
    public function collective()
    {
        return $this->hasOne('App\Qingwuit\Models\Collective', 'goods_id', 'id')->withTrashed();
    }

    // 获取分销ID
    public function distribution()
    {
        return $this->hasOne('App\Qingwuit\Models\Distribution', 'goods_id', 'id')->withTrashed();
    }
}
