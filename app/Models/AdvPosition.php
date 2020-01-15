<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvPosition extends Model
{
    protected $table = "adv_position"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function adv(){
        return $this->hasMany("App\Models\Adv",'ap_id','id');
    }

    public function get_adv_list($ap_name){
        return $this->where('ap_isuse',1)->where('ap_name',$ap_name)->with(['adv'=>function($q){
            $q->where('adv_start','<=',time())->where('adv_end','>=',time())->orderBy('adv_sort','desc');
        }])->first();
    }
}
