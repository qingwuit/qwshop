<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;
    protected $guarded = [];
    protected $dates = ['pay_time','delivery_time','receipt_time','comment_time'];

    public function order_goods()
    {
        return $this->hasMany('App\Qingwuit\Models\OrderGoods', 'order_id', 'id');
    }

    // 获取店铺信息
    public function store()
    {
        return $this->hasOne('App\Qingwuit\Models\Store', 'id', 'store_id');
    }

    // 获取店铺信息
    public function user()
    {
        return $this->hasOne('App\Qingwuit\Models\User', 'id', 'user_id');
    }

    // 获取售后
    public function refund()
    {
        return $this->hasOne('App\Qingwuit\Models\Refund', 'order_id', 'id');
    }

    // // 获取分销日志
    public function distribution(){
        return $this->hasMany('App\Qingwuit\Models\DistributionLog','order_id','id');
    }
}
