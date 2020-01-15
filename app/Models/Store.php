<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = "store"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
