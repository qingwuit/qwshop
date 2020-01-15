<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Area;
use App\Models\GoodsClass;
use App\Models\StoreClass;
use App\Tools\Uploads;

class StoreController extends BaseController
{
    public function setting(Request $req,Store $store_model,Area $area_model){
        $user_info = auth()->user();
        $store_info = $store_model->where('user_id',$user_info['id'])->first();
        if(!$req->isMethod('post')){
            $area_list = $area_model->whereIn('id',[$store_info['province_id'],$store_info['city_id'],$store_info['region_id']])->get();
            $area_info = [$area_list[0]['area_id'],$area_list[1]['area_id'],$area_list[2]['area_id']];
            $store_info['area_info'] = $area_info;
            return $this->success_msg('ok',$store_info);
        }

        $area_list = $area_model->whereIn('area_id',$req->area_info)->get();
        $data = [
            'store_name'            =>  $req->store_name,
            'store_description'     =>  $req->store_description,
            'store_logo'            =>  $req->store_logo,
            'store_mobile'          =>  $req->store_mobile,
            'area_info'             =>  $area_list[0]['name'].' '.$area_list[1]['name'].' '.$area_list[2]['name'],
            'province_id'           =>  $area_list[0]['id'],
            'city_id'               =>  $area_list[1]['id'],
            'region_id'             =>  $area_list[2]['id'],
            'store_address'         =>  $req->store_address,
            'lng'                   =>  $req->lng,
            'lat'                   =>  $req->lat,
        ];
        $store_model->where('user_id',$user_info['id'])->update($data);
        return $this->success_msg();
    }


    // 获取店铺申请的分类
    public function get_store_class(Request $req,StoreClass $store_class_model,Store $store_model,GoodsClass $goods_class_model){
        $user_info = auth()->user();
        $store_info = $store_model->where('user_id',$user_info['id'])->first();
        $class_info = $store_class_model->where('store_id',$store_info['id'])->first();

        if(!isset($req->id)){
            $arr = explode(',',$class_info['class_1']);
            $arr = array_unique($arr);
            $list = $goods_class_model->whereIn('id',$arr)->get();
            return $this->success_msg('ok',$list);
        }
        $deep = $req->deep; // 深度
        if($deep==0){
            $arr = explode(',',$class_info['class_2']);
            $arr = array_unique($arr);
            $list = $goods_class_model->whereIn('id',$arr)->where('pid',$req->id)->get();
        }
        if($deep==1){
            $arr = explode(',',$class_info['class_3']);
            $arr = array_unique($arr);
            $list = $goods_class_model->whereIn('id',$arr)->where('pid',$req->id)->get();
        }

        return $this->success_msg('ok',$list);
    }

    // 设置包邮，多少钱免邮费
    public function free_freight(Request $req,Store $store_model){
        $user_info = auth()->user();
        $store_info = $store_model->select('free_freight','id')->where('user_id',$user_info['id'])->first();
        if(!$req->isMethod('post')){
            return $this->success_msg('ok',floatval($store_info['free_freight']));
        }
        $free_freight = $req->free_freight;
        $rs = $store_model->where('id',$store_info['id'])->update(['free_freight'=>$free_freight]);
        return $this->success_msg('ok',$rs);
    }

    // 设置商品详情 售后服务展示图片
    public function after_sale_content(Request $req,Store $store_model){
        $user_info = auth()->user();
        $store_info = $store_model->select('after_sale_content','id')->where('user_id',$user_info['id'])->first();
        if(!$req->isMethod('post')){
            return $this->success_msg('ok',$store_info['after_sale_content']);
        }
        $after_sale_content = $req->after_sale_content;
        $rs = $store_model->where('id',$store_info['id'])->update(['after_sale_content'=>$after_sale_content]);
        return $this->success_msg('ok',$rs);
    }


    public function logo_upload(Request $req){
        $user_info = auth()->user();
        $uploads_model = new Uploads;
        $data['is_thumb'] = 1; // 缩略图
        $data['filepath'] = 'store/'.$user_info['id'].'/';
        $rs = $uploads_model->uploads($data);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    


}
