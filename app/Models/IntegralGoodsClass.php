<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntegralGoodsClass extends Model
{
    use SoftDeletes;
    public function integral_goods(){
        return $this->hasMany('App\Models\IntegralGoods','cid','id')->withTrashed();
    }
}
