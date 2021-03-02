<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seckill extends Model
{
    use SoftDeletes;
    protected $dates = ['start_time','end_time'];

    public function goods(){
        return $this->hasOne("App\Models\Goods",'id','goods_id')->withTrashed();
    }
}
