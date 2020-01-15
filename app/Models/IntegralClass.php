<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegralClass extends Model
{
    protected $table = "integral_class"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
