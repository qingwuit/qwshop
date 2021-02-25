<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponLog extends Model
{
    use SoftDeletes;
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id')->withTrashed();
    }

    public function store(){
        return $this->hasOne('App\Models\Store','id','store_id')->withTrashed();
    }
}
