<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSpec extends Model
{
    protected $table = "goods_spec"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
