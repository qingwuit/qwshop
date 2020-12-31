<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seckill extends Model
{
    protected $dates = ['start_time','end_time'];

    public function goods(){
        return $this->hasOne("App\Models\Goods",'id','goods_id');
    }
}
