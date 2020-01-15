<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegralOrderGoods extends Model
{
    protected $table = "integral_order_goods"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
