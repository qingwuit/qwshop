<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupbuyUser extends Model
{
    protected $table = "groupbuy_user"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
