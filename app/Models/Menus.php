<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = "menus"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    // public function (){
    // 	return $this->belongsToMany('App\Models\Menus');
    // }
}
