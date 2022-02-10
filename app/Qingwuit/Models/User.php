<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory,HasApiTokens,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function findForPassport($username)
    {
        return $this->orWhere('username', $username)->orWhere('phone', $username)->first(); // ->orWhere('phone', $login)
    }
    public function roles()
    {
        return $this->belongsToMany('App\Qingwuit\Models\UserRole', 'user_to_roles', 'user_id', 'role_id');
    }
}
