<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];
    public function permissions(){
        return $this->belongsToMany('App\Models\Permission','role_permissions','role_id','permission_id');
    }

    public function menus(){
        return $this->belongsToMany('App\Models\Menu','role_menus','role_id','menu_id');
    }
}
