<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponLog extends Model
{
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function store(){
        return $this->hasOne('App\Models\Store','id','store_id');
    }
}
