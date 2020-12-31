<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    // 获取指定的数据
    public function permissions(){
        return $this->hasMany('App\Models\Permission','pid','id');
    }
}
