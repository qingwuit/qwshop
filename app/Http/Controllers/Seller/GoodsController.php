<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\GoodsResource\GoodsTabSellerCollection;
use App\Models\Goods;
use App\Models\GoodsSku;
use App\Services\GoodsService;
use App\Services\StoreService;
use App\Services\UploadService;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Goods $goods_model)
    {
        // 条件筛选
        $goods_params = ['goods_verify'=>1]; // 商品后台审核 还有上架
        if(!empty($request->goods_name)){
            $goods_model = $goods_model->where('goods_name','like','%'.$request->goods_name.'%');
        }
        // if(!empty($request->brand_id)){
        //     $goods_model = $goods_model->where('brand_id',$request->brand_id);
        // }
        // if(!empty($request->class_id)){
        //     $goods_model = $goods_model->where('class_id',$request->class_id);
        // }
        if(!empty($request->goods_no)){
            $goods_model = $goods_model->where('goods_no',$request->goods_no);
        }
        if(isset($request->goods_status) && $request->goods_status !=''){
            $goods_model = $goods_model->where('goods_status',$request->goods_status);
        }
        if(isset($request->goods_verify)){
            $goods_params['goods_verify'] = $request->goods_verify;
        }
        
        $list = $goods_model->where($goods_params)
                            ->where('store_id',$this->get_store(true))
                            ->with(['goods_class','goods_brand','goods_skus'=>function($q){
                                return $q->select('goods_id','goods_price','goods_stock')->orderBy('goods_price','asc');
                            }])
                            ->orderBy('id','desc')
                            ->paginate($request->per_page??30);
        return $this->success(new GoodsTabSellerCollection($list));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,GoodsService $goods_service)
    {
        $info = $goods_service->add();
        if($info['status']){
            return $this->success([],__('goods.add_success'));
        }
        return $this->error(__('goods.add_error'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsService $goods_service,$id)
    {
        $info = $goods_service->getStoreGoodsInfo($id);
        if($info['status']){
            return $this->success($info['data']);
        }
        return $this->error(__('goods.add_error'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,GoodsService $goods_service, $id)
    {
        $info = $goods_service->edit($id);
        if($info['status']){
            return $this->success([],__('goods.add_success'));
        }
        return $this->error(__('goods.add_error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goods $goods_model,GoodsSku $goods_sku_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $goods_list = $goods_model->select('store_id')->whereIn('id',$idArray)->get();
        foreach($goods_list as $v){
            if($v['store_id'] != $this->get_store(true)){
                return $this->error(__('goods.del_error'));
            }
        }
        $goods_model->whereIn('id',$idArray)->delete();
        $goods_sku_model->whereIn('goods_id',$idArray)->delete();
        return $this->success([],__('base.success'));
    }

    // 商家拥有商品栏目信息
    public function store_goods_classes(StoreService $store_service){
        $goods_classes = $store_service->getStoreGoodsClasses($this->get_store(true));
        return $goods_classes['status']?$this->success($goods_classes['data']):$this->error($goods_classes['msg']);
    }

    // 商品图片上传
    public function goods_upload(){
        $upload_service = new UploadService();
        $store_id = $this->get_store(true);
        $rs = $upload_service->goods($store_id);
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
