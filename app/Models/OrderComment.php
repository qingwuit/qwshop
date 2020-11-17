<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderComment extends Model
{
    // 获取店铺信息
    public function goods(){
        return $this->hasOne('App\Models\Goods','id','goods_id');
    }

    // 获取用户信息
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
