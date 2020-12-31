<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $guarded = [];

    public function distribution(){
        return $this->hasOne("App\Models\Distribution","goods_id",'goods_id');
    }

    public function user(){
        return $this->hasOne("App\Models\User","id",'user_id');
    }
}
