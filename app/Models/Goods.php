<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';

    public function goods_class(){
        return $this->hasOne('App\Models\GoodsClass','id','class_id');
    }

    public function goods_brand(){
        return $this->hasOne('App\Models\GoodsBrand','id','brand_id');
    }
}
