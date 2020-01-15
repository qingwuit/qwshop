<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreClass extends Model
{
    protected $table = "store_class"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
