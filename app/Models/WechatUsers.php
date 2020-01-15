<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WechatUsers extends Model
{
    protected $table = "wechat_users"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
