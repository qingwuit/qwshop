<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory,HasApiTokens,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function findForPassport($login)
    {
        return $this->orWhere('username', $login)->first(); // ->orWhere('phone', $login)
    }

    public function roles()
    {
        return $this->belongsToMany('App\Qingwuit\Models\AdminRole', 'admin_to_roles', 'admin_id', 'role_id');
    }
}
