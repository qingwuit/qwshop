<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Models\Seckill;
use App\Models\SeckillGoods;
use Illuminate\Support\Facades\DB;

class SeckillController extends NotokenController
{
    // 获取秒杀活动 未结束的
    public function get_seckill_list(Request $req,Seckill $seckill_model,SeckillGoods $seckill_goods_model){
        $id = $req->id??0;
        $data['seckill_list'] = $seckill_model->where('end_time','>',time())->take(4)->get()->toArray();

        if(empty($data['seckill_list'])){
            return $this->success_msg('没有秒杀活动进行',$data);
        }

        if(empty($id)){
            $id = $data['seckill_list'][0]['id'];
        }

        $seckill_info = [];

        foreach($data['seckill_list'] as $k=>$v){
            if($v['id'] == $id){
                $seckill_info = $v;
            }
            $v['is_active'] = $v['start_time']<time()?true:false;
            $data['seckill_list'][$k] = $v;
        }

        $prefix = DB::getTablePrefix(); // 数据量前缀
        $data['seckill_goods'] = $seckill_goods_model->select(DB::raw('(select count(*) from '.$prefix.'store_comment as B where '.$prefix.'seckill_goods.goods_id = B.goods_id) as comment_count,seckill_goods.*'))->where('status',1)->where('sid',$id)->with(['goods','spec_once'])->paginate(30)->toArray();
        foreach($data['seckill_goods']['data'] as $k=>$v){
            $data['seckill_goods']['data'][$k]['goods']['image'] = get_format_image($data['seckill_goods']['data'][$k]['goods']['goods_master_image'],200);
            if(!empty($v['spec_once'])){
                $data['seckill_goods']['data'][$k]['goods']['goods_price'] = $v['spec_once']['goods_price'];
                $data['seckill_goods']['data'][$k]['goods']['goods_market_price'] = $v['spec_once']['goods_market_price'];
            }
        } 
        $data['seckill_info'] = $seckill_info;

        return $this->success_msg('ok',$data);

        
    }
}
