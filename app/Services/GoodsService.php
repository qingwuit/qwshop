<?php
namespace App\Services;

use App\Http\Resources\Home\GoodsResource\GoodsListCollection;
use App\Http\Resources\Home\GoodsResource\GoodsSearchCollection;
use App\Http\Resources\Home\GoodsResource\SeckillGoodsCollection;
use App\Http\Resources\Home\GoodsResource\StoreGoodsListCollection;
use App\Models\Goods;
use App\Models\GoodsAttr;
use App\Models\GoodsSku;
use App\Models\GoodsSpec;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodsService extends BaseService{
    use HelperTrait;
    protected $status = ['goods_status'=>1,'goods_verify'=>1];
    public function add(){
        $goods_model = new Goods();
        $config_service = new ConfigService;
        $store_id = $this->get_store(true);
        $data = [
            'store_id'              => $store_id??0,
            'goods_name'            => request()->goods_name,                         // 商品名
            'goods_subname'         => request()->goods_subname??'',                  // 副标题
            'goods_no'              => request()->goods_no??'',                       // 商品编号
            'brand_id'              => request()->brand_id,                           // 商品品牌
            'class_id'              => request()->classInfo[2]['id']??0,              // 商品分类
            'goods_master_image'    => request()->goods_master_image,                 // 商品主图
            'goods_price'           => abs(request()->goods_price??0),                // 商品价格
            'goods_market_price'    => abs(request()->goods_market_price??0),         // 商品市场价
            'goods_weight'          => abs(request()->goods_weight??0),               // 商品重量
            'goods_stock'           => abs(request()->goods_stock??0),                // 商品库存
            'goods_content'         => request()->goods_content??'',                  // 商品内容详情
            'goods_content_mobile'  => request()->goods_content_mobile??'',           // 商品内容详情（手机）
            'goods_status'          => abs(request()->goods_status)??0,               // 商品上架状态
            'freight_id'            => abs(request()->freight_id)??0,                 // 运费模版ID
            'goods_images'          => implode(',',request()->goods_images??[]),
        ];

        // 判断是否开启添加商品审核
        if(!empty($config_service->getFormatConfig('goods_verify'))){
            $data['goods_verify'] = 2;
        }

        try{
            DB::beginTransaction();
            $goods_model = $goods_model->create($data);
            // 规格处理
            if(isset(request()->skuList) && !empty(request()->skuList)){
                $skuData = [];
                foreach(request()->skuList as $k=>$v){
                    $skuData[$k]['goods_image'] = $v['goods_image']??''; // sku图片
                    $skuData[$k]['spec_id'] = implode(',',$v['spec_id']); // sku 属性
                    $skuData[$k]['sku_name'] = implode(',',$v['sku_name']); // sku名称
                    $skuData[$k]['goods_price'] = abs($v['goods_price']??0); // sku价格
                    $skuData[$k]['goods_market_price'] = abs($v['goods_market_price']??0); // sku市场价
                    $skuData[$k]['goods_stock'] = abs($v['goods_stock']??0); // sku库存
                    $skuData[$k]['goods_weight'] = abs($v['goods_weight']??0); // sku 重量
                }
                $goods_model->goods_skus()->createMany($skuData);
            }
            DB::commit();
            return $this->format([],__('goods.add_success'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('qwlog')->debug('商品添加失败');
            Log::channel('qwlog')->debug($e->getMessage());
            return $this->format_error(__('goods.add_error'));
        }
        
    }

    // 商品编辑
    public function edit($goods_id){
        $goods_model = new Goods();
        $config_service = new ConfigService;
        $goods_skus_model = new GoodsSku;
        $store_id = $this->get_store(true);
        $goods_model = $goods_model->where(['store_id'=>$store_id,'id'=>$goods_id])->first();

        // 商品名
        if(isset(request()->goods_name) && !empty(request()->goods_name)){
            $goods_model->goods_name = request()->goods_name;
        }
        // 副标题
        if(isset(request()->goods_subname) && !empty(request()->goods_subname)){
            $goods_model->goods_subname = request()->goods_subname;
        }
        // 商品编号
        if(isset(request()->goods_no) && !empty(request()->goods_no)){
            $goods_model->goods_no = request()->goods_no;
        }
        // 商品品牌
        if(isset(request()->brand_id) && !empty(request()->brand_id)){
            $goods_model->brand_id = request()->brand_id;
        }
        // 商品分类
        if(isset(request()->classInfo[2]['id']) && !empty(request()->classInfo[2]['id'])){
            $goods_model->class_id = request()->classInfo[2]['id'];
        }
        // 商品主图
        if(isset(request()->goods_master_image) && !empty(request()->goods_master_image)){
            $goods_model->goods_master_image = request()->goods_master_image;
        }
        // 商品价格
        if(isset(request()->goods_price) && !empty(request()->goods_price)){
            $goods_model->goods_price = abs(request()->goods_price??0);
        }
        // 商品市场价
        if(isset(request()->goods_market_price) && !empty(request()->goods_market_price)){
            $goods_model->goods_market_price = abs(request()->goods_market_price??0);
        }
        // 商品重量
        if(isset(request()->goods_weight) && !empty(request()->goods_weight)){
            $goods_model->goods_weight = abs(request()->goods_weight??0);
        }
        // 商品库存
        if(isset(request()->goods_stock) && !empty(request()->goods_stock)){
            $goods_model->goods_stock = abs(request()->goods_stock??0);
        }
        // 商品内容详情
        if(isset(request()->goods_content) && !empty(request()->goods_content)){
            $goods_model->goods_content = request()->goods_content;
        }
        // 商品内容详情（手机）
        if(isset(request()->goods_content_mobile) && !empty(request()->goods_content_mobile)){
            $goods_model->goods_content_mobile = request()->goods_content_mobile;
        }
        // 商品上架状态
        if(isset(request()->goods_status)){
            $goods_model->goods_status = abs(request()->goods_status??0);
        }
        // 商品上架状态
        if(isset(request()->freight_id)){
            $goods_model->freight_id = abs(request()->freight_id??0);
        }
        // 商品图片
        if(isset(request()->goods_images) && !empty(request()->goods_images)){
            $goods_model->goods_images = implode(',',request()->goods_images??[]);
        }

        // 判断是否开启添加商品审核
        if(!empty($config_service->getFormatConfig('goods_verify'))){
            // 如果是内容标题修改则进行审核（暂时不写）
            $goods_model->goods_verify = 2;
        }

        try{
            DB::beginTransaction();
            $goods_model = $goods_model->save();
            // 规格处理
            if(isset(request()->skuList) && !empty(request()->skuList)){
                $skuData = []; 
                $skuId = []; // 修改的skuID 不存在则准备删除
                foreach(request()->skuList as $k=>$v){
                    if(isset($v['id']) && !empty($v['id'])){
                        // 如果ID不为空则代表存在此sku 进行修改
                        $skuId[] = $v['id'];
                        $goods_skus_model->where('goods_id',$goods_id)->where('id',$v['id'])->update([
                            'goods_image'           => $v['goods_image']??'',// sku图片
                            'spec_id'               => implode(',',$v['spec_id']), // sku 属性
                            'sku_name'              => implode(',',$v['sku_name']), // sku名称
                            'goods_price'           => abs($v['goods_price']??0), // sku价格
                            'goods_market_price'    => abs($v['goods_market_price']??0), // sku市场价
                            'goods_stock'           => abs($v['goods_stock']??0), // sku库存
                            'goods_weight'          => abs($v['goods_weight']??0), // sku 重量
                        ]);
                    }else{
                        // 否则进行插入数据库
                        $skuData[$k]['goods_image'] = $v['goods_image']??''; // sku图片
                        $skuData[$k]['spec_id'] = implode(',',$v['spec_id']); // sku 属性
                        $skuData[$k]['sku_name'] = implode(',',$v['sku_name']); // sku名称
                        $skuData[$k]['goods_price'] = abs($v['goods_price']??0); // sku价格
                        $skuData[$k]['goods_market_price'] = abs($v['goods_market_price']??0); // sku市场价
                        $skuData[$k]['goods_stock'] = abs($v['goods_stock']??0); // sku库存
                        $skuData[$k]['goods_weight'] = abs($v['goods_weight']??0); // sku 重量
                    }
                }

                // 如果ID不为空则代表存在此sku 进行修改
                if(!empty($skuId)){
                    $goods_skus_model->where('goods_id',$goods_id)->whereNotIn('id',$skuId)->delete();
                }

                // 新建不存在sku进行插入数据库
                if(!empty($skuData)){
                    $goods_model = new Goods;
                    $goods_model = $goods_model->find($goods_id);
                    $goods_model->goods_skus()->createMany($skuData);
                }
                
            }else{
                // 清空所有sku
                $goods_skus_model->where('goods_id',$goods_id)->delete();
            }
            DB::commit();
            return $this->format([],__('goods.add_success'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('qwlog')->debug('商品编辑失败');
            Log::channel('qwlog')->debug($e->getMessage());
            return $this->format_error(__('goods.add_error'));
        }
        
    }

    // 修改商品的状态审核
    public function editGoodsVerify($goods_id,$status=1,$msg=''){
        $goods_model = new Goods;
        $goods_model = $goods_model->where('id',$goods_id);
        $data = [
            'goods_verify'      =>  $status,
        ];
        if($status == 0){
            $data['refuse_info'] = $msg;
        }
        $rs = $goods_model->update($data);
        return $this->format($rs);
    }

    // 获取商家的商品详情
    public function getStoreGoodsInfo($id){
        $goods_model = new Goods;
        $goods_skus_model = new GoodsSku();
        $goods_attr_model = new GoodsAttr();
        $goods_spec_model = new GoodsSpec();
        $store_id = $this->get_store(true);
        $goods_info = $goods_model->with('goods_brand')->where('store_id',$store_id)->where('id',$id)->first();
        $goods_info['goods_images'] = explode(',',$goods_info['goods_images']);
        
        // 获取处理后的规格信息
        $sku = $goods_skus_model->where('goods_id',$id)->get()->toArray();
        if(!empty($sku)){
            $skuList = [];
            $spec_id = [];
            foreach($sku as $v){
                $v['spec_id'] = explode(',',$v['spec_id']);
                $v['sku_name'] = explode(',',$v['sku_name']);
                $spec_id = array_merge($spec_id,$v['spec_id']);
                $skuList[] = $v;
            }
            $spec_id = array_unique($spec_id);
            $goods_spec = $goods_spec_model->whereIn('id',$spec_id)->orderBy('id','desc')->get()->toArray();
            $attr_id = [];
            foreach($goods_spec as $v){
                if(!in_array($v['attr_id'],$attr_id)){
                    $attr_id[] = $v['attr_id'];
                }
            }
            $goods_attr = $goods_attr_model->whereIn('id',$attr_id)->with('specs')->orderBy('id','desc')->get()->toArray();
            foreach($goods_attr as $k=>$v){
                foreach($v['specs'] as $key=>$vo){
                    if(in_array($vo['id'],$spec_id)){
                        $goods_attr[$k]['specs'][$key]['check'] = true;
                    }
                }
            }
            $goods_info['attrList'] = $goods_attr;
            $goods_info['skuList'] = $skuList;
        }
        return $this->format($goods_info);
    }

    // 获取商品详情
    public function getGoodsInfo($id,$auth='user'){
        $goods_model = new Goods;
        $store_service = new StoreService();
        $goods_skus_model = new GoodsSku();
        $goods_attr_model = new GoodsAttr();
        $goods_spec_model = new GoodsSpec();
        if($auth != 'admin'){
            $goods_model = $goods_model->where($this->status);
        }
        $goods_info = $goods_model->with('goods_brand')->where('id',$id)->first();

        if(empty($goods_info)){
            return $this->format_error(__('goods.goods_not_found'));
        }

        $store_info = $store_service->getStoreInfo($goods_info['store_id']);

        if(!$store_info['status']){
            return $this->format_error($store_info['msg']);
        }
        
        if($store_info['data']['store_status']!=1 || $store_info['data']['store_verify']!=3){
            return $this->format_error(__('stores.store_not_defined'));
        }

        $goods_info['goods_images'] = explode(',',$goods_info['goods_images']);
        $goods_info['goods_images_thumb_150'] = $this->thumb_array($goods_info['goods_images'],150);
        $goods_info['goods_images_thumb_400'] = $this->thumb_array($goods_info['goods_images'],400);
        
        // 获取处理后的规格信息
        $sku = $goods_skus_model->where('goods_id',$id)->get()->toArray();
        if(!empty($sku)){
            $skuList = [];
            $spec_id = [];
            foreach($sku as $v){
                $v['spec_id'] = explode(',',$v['spec_id']);
                $v['sku_name'] = explode(',',$v['sku_name']);
                $spec_id = array_merge($spec_id,$v['spec_id']);
                $skuList[] = $v;
            }
            $spec_id = array_unique($spec_id);
            $goods_spec = $goods_spec_model->whereIn('id',$spec_id)->orderBy('id','desc')->get()->toArray();
            $attr_id = [];
            foreach($goods_spec as $v){
                if(!in_array($v['attr_id'],$attr_id)){
                    $attr_id[] = $v['attr_id'];
                }
            }
            $goods_attr = $goods_attr_model->whereIn('id',$attr_id)->with('specs')->orderBy('id','desc')->get()->toArray();
            $goods_info['goods_price'] = $sku[0]['goods_price'];
            $goods_info['goods_price'] = $sku[0]['goods_stock'];
            $goods_info['attrList'] = $goods_attr;
            $goods_info['skuList'] = $skuList;
        }

        $goods_class_service = new GoodsClassService;
        $goods_info['goods_class'] = $goods_class_service->getGoodsClassByGoodsId($id)['data'];
        

        return $this->format($goods_info);
    }

    // 获取统计数据
    public function getCount($auth="seller"){
        $goods_model = new Goods();

        if($auth == 'seller'){
            $store_id = $this->get_store(true);
            $data = [
                'wait'  =>  $goods_model->where('goods_verify',2)->where('store_id',$store_id)->count(),
                'refuse'  =>  $goods_model->where('goods_verify',0)->where('store_id',$store_id)->count(),
            ];
        }else{
            $data = [
                'wait'  =>  $goods_model->where('goods_verify',2)->count(),
                'refuse'  =>  $goods_model->where('goods_verify',0)->count(),
            ];
        }
        
        return $data;
    }

    // 搜索
    public function goodsSearch(){
        $goods_model = new Goods;
        $params = request()->params??'';
        try{
            if(!empty($params)){
                $params_array = json_decode(base64_decode($params),true);
                
                // 品牌
                if(isset($params_array['brand_id']) && !empty($params_array['brand_id'])){
                    $goods_model = $goods_model->where('brand_id',$params_array['brand_id']);
                }
    
                // 栏目
                if(isset($params_array['class_id']) && !empty($params_array['class_id'])){
                    if(is_array($params_array['class_id']) ){
                        $goods_model = $goods_model->whereIn('class_id',$params_array['class_id']);
                    }else{
                        $goods_model = $goods_model->where('class_id',$params_array['class_id']);
                    }
                    
                }

                // 商家
                if(isset($params_array['store_id']) && !empty($params_array['store_id'])){
                    $goods_model = $goods_model->where('store_id',$params_array['store_id']);
                }
    
                // 关键词
                if(isset($params_array['keywords']) && !empty($params_array['keywords'])){
                    $params_array['keywords'] = urldecode($params_array['keywords']);
                    $goods_model = $goods_model->where('goods_name','like','%'.$params_array['keywords'].'%');
                }
    
                // 排序
                if(isset($params_array['sort_type']) && !empty($params_array['sort_type'])){
                    $goods_model = $goods_model->orderBy($params_array['sort_type'],$params_array['sort_order']);
                }else{
                    $goods_model = $goods_model->orderBy('id','desc')->orderBy('goods_sale','desc');
                }
                
            }

            // 是否是拼团产品
            if(isset(request()->is_collective) && !empty(request()->is_collective)){
                $goods_model = $goods_model->whereHas('collective');
            }

            // 是否是分销产品
            if(isset(request()->is_distribution) && !empty(request()->is_distribution)){
                $goods_model = $goods_model->whereHas('distribution');
            }

            $list = $goods_model->where($this->status)
                        ->with(['goods_sku'=>function($q){
                            return $q->select('goods_id','goods_price','goods_stock')->orderBy('goods_price','asc');
                        }])
                        ->withCount('order_comment')
                        ->whereHas('store',function($q){
                            return $q->where(['store_status'=>1,'store_verify'=>3]);
                        })
                        ->paginate(request()->per_page??30);
        }catch(\Exception $e){
            Log::channel('qwlog')->debug($e->getMessage());
            return $this->format_error(__('goods.search_error'));
        }
        return $this->format(new GoodsSearchCollection($list));

    }

    // 获取指定条件销售排行
    public function getSaleSortGoods($where,$take=6){
        $goods_model = new Goods();
        $list = $goods_model->whereHas('store',function($q){
            return $q->where(['store_status'=>1,'store_verify'=>3]);
        })->with(['goods_skus'=>function($q){
            return $q->orderBy('goods_price','asc');
        }])->where($where)->where($this->status)->take($take)->orderBy('goods_sale','desc')->get();
        return $this->format(new GoodsListCollection($list) );

    }

    // 商家首页获取商品列表
    public function getHomeStoreGoods($id){
        $goods_model = new Goods;
        $params = request()->params??'';
        try{
            if(!empty($params)){
                $params_array = json_decode(base64_decode($params),true);
                // 排序
                if(isset($params_array['sort_type']) && !empty($params_array['sort_type'])){
                    $goods_model = $goods_model->orderBy($params_array['sort_type'],$params_array['sort_order']);
                }else{
                    $goods_model = $goods_model->orderBy('id','desc')->orderBy('goods_sale','desc');
                }
            }
    
            $list = $goods_model->where('store_id',$id)->where($this->status)
                        ->with(['goods_sku'=>function($q){
                            return $q->select('goods_id','goods_price','goods_stock','goods_market_price')->orderBy('goods_price','asc');
                        }])
                        ->paginate(request()->per_page??30);
        }catch(\Exception $e){
            Log::channel('qwlog')->debug($e->getMessage());
            return $this->format_error(__('goods.search_error'));
        }
        return $this->format(new StoreGoodsListCollection($list));
    }

    // 获取首页秒杀商品
    public function getHomeSeckillGoods(){
        $goods_model = new Goods;
        $list = $goods_model->where($this->status)
                        ->with(['goods_sku'=>function($q){
                            return $q->select('goods_id','goods_price','goods_stock','goods_market_price')->orderBy('goods_price','asc');
                        }])
                        ->whereHas('seckill',function($q){
                            if(empty(request()->start_time)){
                                $q->where('start_time',now()->format('Y-m-d H').':00');
                            }
                            $q->where('start_time',now()->addHours(request()->start_time)->format('Y-m-d H').':00');
                        })
                        ->paginate(request()->per_page??30);
        return $this->format(new SeckillGoodsCollection($list));
    }
}
