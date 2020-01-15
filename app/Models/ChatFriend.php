<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatFriend extends Model
{
    protected $table = "chat_friend"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function chat_msg(){
        return $this->hasOne('\App\Models\ChatMsg','to_user_id','user_id');
    }

    public function not_read(){
        return $this->hasMoney('\App\Models\ChatMsg','to_user_id','user_id');
    }
}
