<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistributionLog extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function user(){
        return $this->hasOne("App\Models\User",'id','user_id')->withTrashed();
    }

    public function store(){
        return $this->hasOne("App\Models\Store",'id','store_id')->withTrashed();
    }

    public function order(){
        return $this->hasOne("App\Models\Order",'id','order_id')->withTrashed();
    }
}
