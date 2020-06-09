<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function permissions(){
        return $this->belongsToMany('App\Models\Permission','menu_id','permission_id');
    }

    public function menus(){
        return $this->belongsToMany('App\Models\Menu','role_id','menu_id');
    }
}
