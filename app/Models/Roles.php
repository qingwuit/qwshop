<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = "roles"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;
    protected $fillable = ['name','content'];

    // 关联到菜单表
    public function menus(){
    	return $this->belongsToMany('App\Models\Menus','role_link_menu','role_id','menu_id')->withPivot('commands');
    }

    // 关联到钩子表
    public function hooks(){
    	return $this->belongsToMany('App\Models\Hooks','role_link_hook','role_id','hook_id');
    }
}
