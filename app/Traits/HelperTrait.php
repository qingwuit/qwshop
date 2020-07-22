<?php

namespace App\Traits;

trait HelperTrait{
    // 递归无线树状结构
    protected function getTree($data,$pid=0,$lev=0){
        static $arr = [];
        foreach($data as $v){
            if($v['pid']==$pid){
                $v['lev'] = $lev;
                $arr[] = $v;
                $this->getTree($data,$v['id'],$lev+1);
            }
        }
        return $arr;
    }

    // 递归无线树状结构 多元数组
    protected function getChildren($data,$pid=0,$lev=0){
        $arr = [];
        foreach($data as $v){
            
            if($v['pid']==$pid){
                $v['lev'] = $lev;
                $v['children'] = $this->getChildren($data,$v['id'],$lev+1);
                if(count($v['children'])<=0){
                    unset($v['children']);
                }
                $arr[] = $v;
            }
        }
        return $arr;
    }

    // 递归无线树状结构 多元数组
    protected function getAreaChildren($data,$pid=0,$lev=0){
        $arr = [];
        foreach($data as $v){
            
            if($v['pid']==$pid){
                $v['lev'] = $lev;
                $v['children'] = $this->getAreaChildren($data,$v['code'],$lev+1);
                if(count($v['children'])<=0){
                    unset($v['children']);
                }
                $arr[] = $v;
            }
        }
        return $arr;
    }
}