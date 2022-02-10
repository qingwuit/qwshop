<?php

namespace App\Qingwuit\Models;

use App\Qingwuit\Traits\TimeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory,SoftDeletes,TimeTrait;

    protected $guarded = [];

    public function storeClasses()
    {
        return $this->hasOne('App\Qingwuit\Models\StoreClass', 'store_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Qingwuit\Models\OrderComment', 'store_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Qingwuit\Models\Order', 'store_id', 'id');
    }
}
