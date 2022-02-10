<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function goods()
    {
        return $this->hasOne('App\Qingwuit\Models\Goods', 'id', 'goods_id')->withTrashed();
    }

    public function store()
    {
        return $this->hasOne('App\Qingwuit\Models\Store', 'id', 'store_id')->withTrashed();
    }

    public function carts()
    {
        return $this->hasMany('App\Qingwuit\Models\Cart', 'store_id', 'store_id');
    }

    public function goods_sku()
    {
        return $this->hasOne('App\Qingwuit\Models\GoodsSku', 'id', 'sku_id');
    }
}
