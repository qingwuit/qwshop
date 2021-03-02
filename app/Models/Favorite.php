<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public function goods(){
        return $this->hasOne('App\Models\Goods','id','out_id');
    }

    public function store(){
        return $this->hasOne('App\Models\Store','id','out_id');
    }
}
