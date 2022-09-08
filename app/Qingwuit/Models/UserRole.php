<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany('App\Qingwuit\Models\UserPermission', 'user_to_permissions', 'role_id', 'permission_id');
    }

    public function menus()
    {
        return $this->belongsToMany('App\Qingwuit\Models\UserMenu', 'user_to_menus', 'role_id', 'menu_id')->orderBy('is_sort', 'asc');
    }
}
