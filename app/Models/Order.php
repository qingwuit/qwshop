<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function order_goods(){
        return $this->hasMany('App\Models\OrderGoods','order_id','id');
    }
}
