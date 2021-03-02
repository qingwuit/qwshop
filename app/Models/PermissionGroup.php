<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionGroup extends Model
{
    use SoftDeletes;
    // 获取指定的数据
    public function permissions(){
        return $this->hasMany('App\Models\Permission','pid','id');
    }
}
