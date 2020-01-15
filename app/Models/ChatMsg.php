<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMsg extends Model
{
    protected $table = "chat_msg"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
}
