<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seckill extends Model
{
    protected $table = "seckill"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
