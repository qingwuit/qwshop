<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegralOrder extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function order_goods(){
        return $this->hasMany('App\Models\IntegralOrderGoods','order_id','id');
    }
}
