<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
use App\Models\Store;
use App\Services\GoodsService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    // 获取店铺列表
    public function index(StoreService $store_service){
        $info = $store_service->getHomeStoreList();
        if($info['status']){
            return $this->success($info['data']);
        }else{
            return $this->error($info['msg']);
        }
    }

    // 获取店铺信息
    public function get_store_info(StoreService $store_service,GoodsService $goods_service,$id){
        $store_info = $store_service->getStoreInfoAndRate($id,'id,store_name,store_company_name,area_info,store_address,store_slide');
        if($store_info['status']){
            $slide = [];
            if(!empty($store_info['data']['store_slide'])){
                $slide_demo = explode(',',$store_info['data']['store_slide']);
                foreach($slide_demo as $v){
                    $slide[] = ['image_url'=>$v];
                }
            }
            $store_info['data']['store_slide'] = $slide;
            $store_info['data']['sale_list'] = $goods_service->getSaleSortGoods(['store_id'=>$id])['data'];
            return $this->success($store_info['data']);
        }
    }

    // 获取店铺首页的商品
    public function get_store_goods_list(GoodsService $goods_service,$id){
        $info = $goods_service->getHomeStoreGoods($id);
        if($info['status']){
            return $this->success($info['data']);
        }else{
            return $this->error($info['msg']);
        }
    }

    // 根据用户ID获取店铺信息
    public function store_verify(StoreService $store_service){
        $info = $store_service->getStoreVerify();
        if($info['status']){
            return $this->success($info['data']);
        }else{
            return $this->error($info['msg']);
        }
    }

    // 商家入驻接口\
    public function store_join(Request $request,Store $store_model,StoreService $store_service){
        if(!$request->isMethod('post')){
            $info = $store_service->getAuthStoreInfo();
            if(!$info['status']){
                return $this->error($info['msg']);
            }
            return $this->success($info['data']);
        }
        
        $store_verify = $store_service->getStoreVerify();
        
        // 店铺是否存在
        if(!$store_verify['status']){
            $create_info = $store_service->createStore();
            return $create_info['status']?$this->success($create_info['data'],__('base.success')):$this->error($create_info['msg']);
        }

        // 填写入驻资料
        if($store_verify['data']['store_verify'] == 1){
            $edit_info = $store_service->editStore($store_verify['data']['id']);
            $store_verify_status = $store_service->editStoreStatus($store_verify['data']['id'],['store_verify'=>2]);
            return ($edit_info['status'] && $store_verify_status['status'])?$this->success($edit_info['data'],__('store.store_success')):$this->error($edit_info['msg']);
        }

        // 填写入驻资料
        if($store_verify['data']['store_verify'] == 0){
            $store_verify_status = $store_service->editStoreStatus($store_verify['data']['id'],['store_verify'=>1]);
            return  $store_verify_status['status']?$this->success($store_verify_status['data'],__('stores.store_success')):$this->error($store_verify_status['msg']);
        }

        return $this->success([],__('base.store_success'));

    }

    // 图片上传
    public function store_join_upload(Request $request,UploadService $upload_service){
        $store_id = $this->get_store(true);
        $name = $request->name??'';
        $rs = $upload_service->store_join($store_id);
        if($rs['status']){
            return $this->success(['url'=>$rs['data'],'name'=>$name],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
