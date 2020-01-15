<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache; // 缓存不然处理太慢了

class Area extends Model
{
    protected $table = "area"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    public function get_area_list(){
        // 获取省市区数据
        if (!Cache::has('area')) {
            $area_list = $this->get();
            $area_list = getAreaChild($area_list);
            Cache::put('area', json_encode($area_list));
        }else{
            $area_list = json_decode(Cache::get('area'));
        }
        return $area_list;
    }

    public function get_area_tree(){
        // 获取省市区数据
        if (!Cache::has('area_tree')) {
            $area_list = $this->get();
            $area_list = getAreaTree($area_list);
            Cache::put('area', json_encode($area_list));
        }else{
            $area_list = json_decode(Cache::get('area_tree'));
        }
        return $area_list;
    }

    public function getAreaInfoById($id){
        return $this->find($id);
    }

    public function getAreaChildren($area_id){
        return getAreaChildNode($this->get(),$area_id);
    }
}
