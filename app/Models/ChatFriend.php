<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatFriend extends Model
{
    protected $guarded = [];
    public function store(){
        return $this->hasOne('App\Models\Store','id','store_id');
    }

    public function chat_msg(){
        return $this->hasOne('App\Models\ChatMsg','store_id','store_id');
    }
    public function chat_msgs(){
        return $this->hasMany('App\Models\ChatMsg','store_id','store_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
