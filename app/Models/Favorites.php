<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    protected $table = "favorites"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function goods(){
        return $this->hasOne('App\Models\Goods','id','mix_id');
    }

    public function store(){
        return $this->hasOne('App\Models\Store','id','mix_id');
    }

    public function spec_once(){
        return $this->hasOne('App\Models\GoodsSpec','goods_id','mix_id');
    }
}
