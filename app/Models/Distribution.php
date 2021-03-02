<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribution extends Model
{
    use SoftDeletes;
    public function goods(){
        return $this->hasOne("App\Models\Goods",'id','goods_id')->withTrashed();
    }

    public function distribution_logs(){
        return $this->hasOne("App\Models\DistributionLog",'distribution_id','id');
    }
}
