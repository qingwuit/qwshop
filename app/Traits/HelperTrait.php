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

    /**
     * 
     * 获取图片的缩略图
     * @author 青梧系统 <www.qwsystem.com>
     * 
     */

    public function thumb($path,$size='300'){
        $len = strripos($path,'.');
        $ext = substr($path,$len);
        $name = substr($path,0,$len);
        return $name.'_'.$size.$ext;
    }

    /**
     * 
     * 获取图片的缩略图
     * @author 青梧系统 <www.qwsystem.com>
     * 
     */

    public function thumb_array($arr,$size='150'){
        $data = [];
        foreach($arr as $path){
            $len = strripos($path,'.');
            $ext = substr($path,$len);
            $name = substr($path,0,$len);
            $data[] = $name.'_'.$size.$ext;
        }
        return $data;
        
    }

}