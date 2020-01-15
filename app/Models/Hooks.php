<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hooks extends Model
{
    protected $table = "hooks"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
