<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntegralGoods;
use App\Services\UploadService;
use Illuminate\Http\Request;

class IntegralGoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,IntegralGoods $ig_model)
    {
        if(!empty($request->goods_name)){
            $ig_model = $ig_model->where('goods_name','like','%'.$request->goods_name.'%');
        }
        if(isset($request->goods_status)){
            $ig_model = $ig_model->where('goods_status',$request->goods_status);
        }
        $list = $ig_model->orderBy('id','desc')
                         ->paginate($request->per_page??30);
        return $this->success($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,IntegralGoods $ig_model)
    {
        $data = [
            'goods_name'            => $request->goods_name,                         // 商品名
            'goods_subname'         => $request->goods_subname??'',                  // 副标题
            'cid'                   => $request->cid??0,                             // 商品分类
            'goods_master_image'    => $request->goods_master_image,                 // 商品主图
            'goods_price'           => abs($request->goods_price??0),                // 商品价格
            'goods_market_price'    => abs($request->goods_market_price??0),         // 商品市场价
            'goods_stock'           => abs($request->goods_stock??0),                // 商品库存
            'goods_content'         => $request->goods_content??'',                  // 商品内容详情
            'goods_content_mobile'  => $request->goods_content_mobile??'',           // 商品内容详情（手机）
            'goods_status'          => abs($request->goods_status)??0,               // 商品上架状态
            'is_recommend'          => abs($request->is_recommend)??0,               // 商品推荐状态
            'goods_images'          => implode(',',$request->goods_images??[]),
        ];
        $ig_model->create($data);
        return $this->success([],__('base.success'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IntegralGoods $ig_model,$id)
    {
        $info = $ig_model->find($id);
        $info['goods_images'] = explode(',',$info['goods_images']);
        return $this->success($info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,IntegralGoods $ig_model, $id)
    {
        $ig_model = $ig_model->find($id);
        // 商品名
        if(isset($request->goods_name) && !empty($request->goods_name)){
            $ig_model->goods_name = $request->goods_name;
        }
        // 副标题
        if(isset($request->goods_subname) && !empty($request->goods_subname)){
            $ig_model->goods_subname = $request->goods_subname;
        }
        // 商品分类
        if(isset($request->cid) && !empty($request->cid)){
            $ig_model->cid = $request->cid;
        }
        // 商品主图
        if(isset($request->goods_master_image) && !empty($request->goods_master_image)){
            $ig_model->goods_master_image = $request->goods_master_image;
        }
        // 商品价格
        if(isset($request->goods_price) && !empty($request->goods_price)){
            $ig_model->goods_price = abs($request->goods_price??0);
        }
        // 商品市场价
        if(isset($request->goods_market_price) && !empty($request->goods_market_price)){
            $ig_model->goods_market_price = abs($request->goods_market_price??0);
        }
        // 商品库存
        if(isset($request->goods_stock) && !empty($request->goods_stock)){
            $ig_model->goods_stock = abs($request->goods_stock??0);
        }
        // 商品内容详情
        if(isset($request->goods_content) && !empty($request->goods_content)){
            $ig_model->goods_content = $request->goods_content;
        }
        // 商品内容详情（手机）
        if(isset($request->goods_content_mobile) && !empty($request->goods_content_mobile)){
            $ig_model->goods_content_mobile = $request->goods_content_mobile;
        }
        // 商品上架状态
        if(isset($request->goods_status)){
            $ig_model->goods_status = abs($request->goods_status??0);
        }
        // 商品推荐状态
        if(isset($request->is_recommend)){
            $ig_model->is_recommend = abs($request->is_recommend??0);
        }
        // 商品图片
        if(isset($request->goods_images) && !empty($request->goods_images)){
            $ig_model->goods_images = implode(',',$request->goods_images??[]);
        }

        $ig_model->save();
        return $this->success([],_('base.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IntegralGoods $ig_model,$id)
    {
        $idArray = array_filter(explode(',',$id),function($item){
            return is_numeric($item);
        });
        $ig_model->destroy($idArray);
        return $this->success([],__('base.success'));
    }

    // 商品图片上传
    public function goods_upload(){
        $upload_service = new UploadService();
        $rs = $upload_service->integral();
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
