<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsBrand extends Model
{
    protected $table = "goods_brand"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
