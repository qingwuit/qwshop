<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectiveLog extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function goods(){
        return $this->hasOne("App\Models\Goods","id","goods_id")->withTrashed();
    }

    public function user(){
        return $this->hasOne("App\Models\User","id","user_id")->withTrashed();
    }

    public function orders(){
        return $this->hasMany('App\Models\Order','collective_id','id'); // 这里是团日志ID 非团ID
    }
}
