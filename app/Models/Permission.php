<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;
    public function permission_group(){
        return $this->hasOne('App\Models\PermissionGroup','id','pid')->withTrashed();
    }
}
