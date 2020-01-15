<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // 事物用到
use App\Models\Goods;
use App\Tools\Uploads;
use App\Models\GoodsBrand;
use App\Models\GoodsSpec;
use App\Models\AttrSpec;
use App\Models\Config;
use App\Models\FreightTemplate;
use App\Models\GoodsClass;
use App\Models\StoreGoodsClass;

/**
 * GoodsController class
 *
 * @time 2019/10/22
 * @Description 商品添加控制器
 * @author hg <www.qingwuit.com>
 * 
 */
class GoodsController extends BaseController
{
    public function index(Request $req,Goods $goods_model){

        if(!empty($req->goods_name)){
            $goods_model = $goods_model->where('goods_name','like','%'.$req->goods_name.'%');
        }

        $user_info = auth()->user();
        $list = $goods_model->where('user_id',$user_info['id'])->with('spec')->orderBy('id','desc')->paginate(20);
        return $this->success_msg('Success',$list);
    }

    // 添加商品
    public function add(Request $req,Goods $goods_model,GoodsBrand $goods_brand_model,GoodsSpec $goods_spec_model,Config $config_model,GoodsClass $goods_class_model,StoreGoodsClass $store_goods_class_model){
        if(!$req->isMethod('post')){
            $class_id = intval($_GET['class_id']);
            try{
                $class_list2 = $goods_class_model->find($class_id);
                $class_list1 = $goods_class_model->find($class_list2['pid']);
                $class_list0 = $goods_class_model->find($class_list1['pid']);
                $data['class_list'][] = $class_list0;
                $data['class_list'][] = $class_list1;
                $data['class_list'][] = $class_list2;
                if(empty($data['class_list'][0]) || empty($data['class_list'][1]) || empty($data['class_list'][2])){
                    return $this->error_msg('栏目不能为空');
                }
            }catch(\Exception $e){
                return $this->error_msg($e->getMessage());
            }
            
            $data['goods_brand_list'] = $goods_brand_model->get();
            $data['store_goods_class'] = $store_goods_class_model->get();
            return $this->success_msg('Success',$data);
        }
        $user_info = auth()->user();
        
        $goods_images = implode(',',$req->file_list);                           // 商品图片
        $config_info = get_format_config($config_model->get());                 // 后台配置的查询
        $goods_verify = empty($config_info['goods_verify'])?1:2;                // 根据配置生成商品状态
        $chose_spec_id = '';
        if(isset($req->chose_spec)){                                            // 选择的规格 非使用属性
            foreach($req->chose_spec as $v){
                if($chose_spec_id == ''){
                    $chose_spec_id = $v['id'];
                }else{
                    $chose_spec_id .= ','.$v['id'];
                }
            }
        }                                                    
        
        $chose_attr = '';                                                       // 选择属性
        if(!empty($req->chose_attr)){
            $chose_attr = implode(',',$req->chose_attr);
        }

        $postData = [
            'user_id'               =>  intval($user_info['id']),               // 用户ID
            'cid'                   =>  intval($req->cid),                      // 分类ID
            'bid'                   =>  intval($req->bid),                      // 品牌ID
            'goods_no'              =>  $req->goods_no??'',                     // 商品编号
            'goods_name'            =>  $req->goods_name,                       // 商品名
            'goods_subname'         =>  $req->goods_subname??'',                // 商品副标题
            'goods_price'           =>  abs(floatval($req->goods_price)),       // 商品价格
            'goods_market_price'    =>  floatval($req->goods_market_price),     // 商品市场价格
            'goods_num'             =>  abs(intval($req->goods_num)),           // 商品库存
            'goods_images'          =>  $goods_images,                          // 商品图片
            'goods_master_image'    =>  $req->goods_master_image,               // 商品主图片
            'goods_freight'         =>  abs(floatval($req->goods_freight)),     // 商品运费
            'freight_id'            =>  $req->freight_id??0,                    // 商品运费模版
            'chose_spec_id'         =>  $chose_spec_id,                         // 选择的规格 (为了方便编辑存储)
            'chose_attr'            =>  $chose_attr,                            // 选择的属性 (为了方便编辑存储)
            'is_groupbuy'           =>  $req->is_groupbuy??0,                   // 是否参加拼团
            'groupbuy_price'        =>  $req->groupbuy_price??0,                // 拼团商品价格
            'groupbuy_num'          =>  $req->groupbuy_num??0,                  // 拼团需要人数
            'goods_status'          =>  $req->goods_status,                     // 商品状态 上架 下架
            'goods_verify'          =>  $goods_verify,                          // 商品审核状态 1 通过 0 审核失败 2 审核中
            'goods_content'         =>  empty($req->content)?'':$req->content,  // 商品内容
            'store_goods_class'     =>  $req->store_goods_class??0,             // 自定义分类
            'is_top'                =>  $req->is_top,                           // 是否置顶
            'add_time'              =>  time(),
            'edit_time'             =>  time(),
        ];
        DB::beginTransaction();
        try{
            $goods_id = $goods_model->insertGetId($postData); // 插入商品表

            // 循环插入属性
            if(isset($req->spec_attr)){
                $spec_data = [];
                foreach($req->spec_attr as $v){
                    $spec_info = [];
                    $spec_info['goods_id'] = $goods_id;
                    $spec_info['spec_name'] = $v['attr_str'];
                    $spec_info['goods_price'] = abs(floatval($v['price']));
                    $spec_info['goods_market_price'] = abs(floatval($v['market_price']));
                    $spec_info['goods_num'] = abs(intval($v['num']));
                    $spec_data[] = $spec_info;
                }
                
                if(!empty($spec_data)){
                    $goods_spec_model->insert($spec_data); // 插入数据
                }
            }
            
            DB::commit(); // 提交事物
            return $this->success_msg('Success');
        }catch(\Exception $e){
            DB::rollBack(); // 回滚
            return $this->error_msg($e->getMessage());
        }
        
    }

    // 编辑商品
    public function edit(Request $req,Goods $goods_model,GoodsBrand $goods_brand_model,GoodsSpec $goods_spec_model,Config $config_model,AttrSpec $attr_spec_model,GoodsClass $goods_class_model,FreightTemplate $freight_template_model,StoreGoodsClass $store_goods_class_model,$id){
        if(!$req->isMethod('post')){
            $data['goods_brand_list'] = $goods_brand_model->get();
            $data['store_goods_class'] = $store_goods_class_model->get();
            $data['info'] = $goods_model->with('spec')->find($id);
            if(!empty($data['info']['freight_id'])){
                $data['info']['freight_chose'] = 1;
                $data['freight_info'] = $freight_template_model->find($data['info']['freight_id']);
            }
            $data['chose_spec_list'] = [];
            $data['chose_attr'] = [];
            if(!empty($data['info']['chose_attr'])){
                $data['chose_spec_list'] = $attr_spec_model->whereIn('id',explode(',',$data['info']['chose_spec_id']))->get();
                $data['chose_attr'] = explode(',',$data['info']['chose_attr']);
            }

            // 获取该产品的规格属性
            $spec_list = $goods_spec_model->where('goods_id',$id)->get();
            $data['spec_list'] = [];
            foreach($spec_list as $v){
                $spec_list_info = [];
                $spec_list_info['attr_str'] = $v['spec_name'];
                $spec_list_info['price'] = $v['goods_price'];
                $spec_list_info['market_price'] = $v['goods_market_price'];
                $spec_list_info['num'] = $v['goods_num'];
                $spec_list_info['is_chose'] = true;
                $spec_list_info['attr'] = explode(' ',$v['spec_name']);
                $data['spec_list'][] = $spec_list_info;
            }

            $class_id = intval($data['info']['cid']);
            try{
                $class_list2 = $goods_class_model->find($class_id);
                $class_list1 = $goods_class_model->find($class_list2['pid']);
                $class_list0 = $goods_class_model->find($class_list1['pid']);
                $data['class_list'][] = $class_list0;
                $data['class_list'][] = $class_list1;
                $data['class_list'][] = $class_list2;
                if(empty($data['class_list'][0]) || empty($data['class_list'][1]) || empty($data['class_list'][2])){
                    return $this->error_msg('栏目不能为空');
                }
            }catch(\Exception $e){
                return $this->error_msg($e->getMessage());
            }

            return $this->success_msg('Success',$data);
        }
        $user_info = auth()->user();
        
        $goods_images = implode(',',$req->file_list);                           // 商品图片
        $config_info = get_format_config($config_model->get());                 // 后台配置的查询
        $goods_verify = empty($config_info['goods_verify'])?1:2;                // 根据配置生成商品状态
        $chose_spec_id = '';                                                    // 选择的规格 非使用属性
        if(isset($req->chose_spec)){                                            // 选择的规格 非使用属性
            foreach($req->chose_spec as $v){
                if($chose_spec_id == ''){
                    $chose_spec_id = $v['id'];
                }else{
                    $chose_spec_id .= ','.$v['id'];
                }
            }
        }  
        $chose_attr = '';                                                       // 选择属性
        if(!empty($req->chose_attr)){
            $chose_attr = implode(',',$req->chose_attr);
        }

        $postData = [
            'user_id'               =>  intval($user_info['id']),                           // 用户ID
            'cid'                   =>  intval($req->cid),                                  // 分类ID
            'bid'                   =>  intval($req->bid),                                  // 品牌ID
            'goods_no'              =>  $req->goods_no??'',                                 // 商品编号
            'goods_name'            =>  $req->goods_name,                                   // 商品名
            'goods_subname'         =>  $req->goods_subname??'',                            // 商品副标题
            'goods_price'           =>  abs(floatval($req->goods_price)),                   // 商品价格
            'goods_market_price'    =>  floatval($req->goods_market_price),                 // 商品市场价格
            'goods_num'             =>  abs(intval($req->goods_num)),                       // 商品库存
            'goods_images'          =>  $goods_images,                                      // 商品图片
            'goods_master_image'    =>  $req->goods_master_image,                           // 商品主图片
            'goods_freight'         =>  abs(floatval($req->goods_freight)),                 // 商品运费
            'freight_id'            =>  $req->freight_id??0,                                // 商品运费模版
            'chose_spec_id'         =>  $chose_spec_id,                                     // 选择的规格 (为了方便编辑存储)
            'chose_attr'            =>  $chose_attr,                                        // 选择的属性 (为了方便编辑存储)
            'is_groupbuy'           =>  $req->is_groupbuy??0,                               // 是否参加拼团
            'groupbuy_price'        =>  $req->groupbuy_price??0,                            // 拼团商品价格
            'groupbuy_num'          =>  $req->groupbuy_num??0,                              // 拼团需要人数
            'goods_status'          =>  $req->goods_status,                                 // 商品状态 上架 下架
            'goods_verify'          =>  $goods_verify,                                      // 商品审核状态 1 通过 0 审核失败 2 审核中
            'goods_content'         =>  empty($req->goods_content)?'':$req->goods_content,  // 商品内容
            'store_goods_class'     =>  $req->store_goods_class??0,                         // 自定义分类
            'is_top'                =>  $req->is_top,                                       // 是否置顶
            'edit_time'             =>  time(),
        ];
        DB::beginTransaction();
        try{
            $goods_model->where('id',$id)->update($postData); // 修改商品表

            $goods_spec_model->where('goods_id',$id)->delete(); // 删除原来的数据重新插入规格数据

            // 循环插入属性
            if(isset($req->spec_attr)){
                $spec_data = [];
                foreach($req->spec_attr as $v){
                    $spec_info = [];
                    $spec_info['goods_id'] = $id;
                    $spec_info['spec_name'] = $v['attr_str'];
                    $spec_info['goods_price'] = abs(floatval($v['price']));
                    $spec_info['goods_market_price'] = abs(floatval($v['market_price']));
                    $spec_info['goods_num'] = abs(intval($v['num']));
                    $spec_data[] = $spec_info;
                }
                
                if(!empty($spec_data)){
                    $goods_spec_model->insert($spec_data); // 插入数据
                }
            }
            
            DB::commit(); // 提交事物
            return $this->success_msg('Success');
        }catch(\Exception $e){
            DB::rollBack(); // 回滚
            return $this->error_msg($e->getMessage());
        }
    }

    // 删除商品
    public function del(Request $req,Goods $goods_model,GoodsSpec $goods_spec_model){
        $id = $req->id;
        $user_info = auth()->user();
        $ids = explode(',',$id);
        $goods_model->destroy($ids);
        $goods_spec_model->whereIn('goods_id',$ids)->delete();
        return $this->success_msg();
    }


    // 指定上架
    public function goods_status(Request $req,Goods $goods_model){
        $id = $req->id;
        $user_info = auth()->user();
        $goods_info = $goods_model->find($id);
        $goods_status = $goods_info['goods_status']==1?0:1;
        $goods_model->where('user_id',$user_info['id'])->where('id',$id)->update(['goods_status'=>$goods_status]);
        return $this->success_msg();
    }

    // 商品图片上传
    public function goods_upload(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['filepath'=>'goods/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }
}
