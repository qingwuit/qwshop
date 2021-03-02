<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsAttr extends Model
{
    use SoftDeletes;
    protected $table = 'goods_attrs';
    protected $fillable = ['name','store_id'];
    public function specs(){
        return $this->hasMany('App\Models\GoodsSpec','attr_id','id');
    }
}
