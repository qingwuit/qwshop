<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache; // 缓存不然处理太慢了
use App\Models\Goods;
use Illuminate\Support\Facades\DB;

class GoodsClass extends Model
{
    protected $table = "goods_class"; //指定表
    protected $primaryKey = "id"; //指定id字段
    public $timestamps = false;

    protected $params = ['goods_status'=>1,'goods_verify'=>1];  // 上架   和   审核通过

    public function get_goods_class_list(){
        // 获取省市区数据
        if (!Cache::has('goods_class')) {
            $goods_class_list = $this->orderBy('is_sort','asc')->get();
            $goods_class_list = getChild($goods_class_list);
            Cache::put('goods_class', json_encode($goods_class_list));
        }else{
            $goods_class_list = json_decode(Cache::get('goods_class'));
        }
        return $goods_class_list;
    }

    // 根据id 获取三层数据
    public function getBreadCrumbById($id){
        $list = [];
        $info = $this->find($id);
        if(empty($info)){
            return $list;
        }
        $info2 = $this->find($info['pid']);
        $info3 = $this->find($info2['pid']);
        $list[] = ['name'=>$info3['name'],'id'=>$info3['id']];
        $list[] = ['name'=>$info2['name'],'id'=>$info2['id']];
        $list[] = ['name'=>$info['name'],'id'=>$info['id']];
        return $list;
    }

    // 根据栏目获取所有商品 take 为首页显示条数目
    public function getGoodsListByClassId($ids,$where=[],$order=[],$take=0){
        $class_list = $this->select('id','pid')->whereIn('id',$ids)->get()->toArray();
        $thr_class_ids = []; // 第三层ID
        $fir_class_ids = []; // 第一层ID
        if($class_list[0]['pid'] == 0){  // 顶级栏目
            $sec_class_ids = []; // 第二层ID
            foreach($class_list as $v){
                $fir_class_ids[] = $v['id'];
            }
            $sec_class_list = $this->select('id','pid')->whereIn('pid',$fir_class_ids)->get();
            foreach($sec_class_list as $v){
                $sec_class_ids[] = $v['id'];
            }
            $thr_class = $this->select('id','pid')->whereIn('pid',$sec_class_ids)->get();
            foreach($thr_class as $v){
                $thr_class_ids[] = $v['id'];
            }
        }else{
            $class_info = $this->select('id','pid')->where('id',$class_list[0]['pid'])->first();

            // 第二层
            if($class_info['pid'] == 0){
                $sec_class_ids = []; // 第二层ID
                foreach($class_list as $v){
                    $sec_class_ids[] = $v['id'];
                }
                $thr_class = $this->select('id','pid')->whereIn('pid',$sec_class_ids)->get();
                foreach($thr_class as $v){
                    $thr_class_ids[] = $v['id'];
                }
            }else{  // 第三层栏目
                foreach($class_list as $v){
                    $thr_class_ids[] = $v['id'];
                }
            }
        }

        // 开始获取商品
        $goods_model = new Goods();
        if(!empty($where)){
            $goods_model = $goods_model->where($where);
        }

        if(!empty($order)){
            foreach($order['list'] as $v){
                $goods_model = $goods_model->orderBy($v,$order['order_type']);
            }
        }

        if(!empty($take)){
            $goods_model->take($take);
        }

        $goods_list = $goods_model->with('spec_once')->where($this->params)->whereIn('cid',$thr_class_ids)->get()->toArray();

        // 取出缩略图
        foreach($goods_list as $k=>$v){
            $v['image'] = get_format_image($v['goods_master_image'],200);
            if(!empty($v['spec_once'])){
                $v['goods_price'] = $v['spec_once']['goods_price'];
                $v['goods_market_price'] = $v['spec_once']['goods_market_price'];
            }
            $goods_list[$k] = $v;
        }

        return $goods_list;
    }

    // 搜索产品
    public function searchGoods($class_id,$where=[],$order=[],$keywords="",$page=30){

        // 商品模型实例化
        $goods_model = new Goods();
        $prefix = DB::getTablePrefix(); // 数据量前缀

        // select 子查询评论数量
        $goods_model = $goods_model->select(DB::raw('(select count(*) from '.$prefix.'store_comment as B where '.$prefix.'goods.id = B.goods_id) as comment_count,goods.*'));

        if(!empty($class_id)){
            $class_info = $this->find($class_id);
            $thr_class_ids = []; // 第三层ID
            $sec_class_ids = []; // 第二层ID
            if(empty($class_info['pid'])){  // 一级栏目
                $sec_list = $this->select('id','pid')->where('pid',$class_info['id'])->get()->toArray();
                foreach($sec_list as $v){
                    $sec_class_ids[] = $v['id'];
                }
                $thr_list = $this->select('id','pid')->whereIn('pid',$sec_class_ids)->get()->toArray();
                foreach($thr_list as $v){
                    $thr_class_ids[] = $v['id'];
                }
            }else{
                $class_info2 = $this->find($class_info['pid']);
                if(empty($class_info2['pid'])){  // 二级栏目
                    $thr_list = $this->select('id','pid')->where('pid',$class_info['id'])->get()->toArray();
                    foreach($thr_list as $v){
                        $thr_class_ids[] = $v['id'];
                    }
                }else{ // 三级栏目
                    $thr_class_ids[] = $class_id;
                }
            }

            $goods_model = $goods_model->whereIn('cid',$thr_class_ids);
        }
        
        // 普通条件
        if(!empty($where)){
            $goods_model = $goods_model->where($where);
        }

        // 排序条件
        if(!empty($order)){
            if(!empty($order)){
                foreach($order['list'] as $v){
                    $goods_model = $goods_model->orderBy($v,$order['order_type']);
                }
            }
        }

        // 关键词条件
        if(!empty($keywords)){
            $goods_model = $goods_model->where('goods_name','like','%'.$keywords.'%');
        }

        $goods_res = $goods_model->with('spec_once')->paginate($page)->toArray();
        
        $store_comment_model = new StoreComment();

        // 是否有规格，有规格则取规格价格
        foreach($goods_res['data'] as $k=>$v){
            if(!empty($v['spec_once'])){
                $v['goods_price'] = $v['spec_once']['goods_price'];
                $v['goods_market_price'] = $v['spec_once']['goods_market_price'];
            }
            $v['image'] = get_format_image($v['goods_master_image'],200);
            // $v['comment_count'] = $store_comment_model->where('goods_id',$v['id'])->count();
            $goods_res['data'][$k] = $v;
        }
        $goods_res['data'] = get_format_image_goods_list($goods_res['data']);
        return $goods_res;
        
    }

}
