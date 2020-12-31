<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegralGoodsClass extends Model
{
    public function integral_goods(){
        return $this->hasMany('App\Models\IntegralGoods','cid','id');
    }
}
