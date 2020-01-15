<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCkeck extends Model
{
    protected $table = "user_check"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
