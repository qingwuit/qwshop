<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Seckill;
use App\Models\SeckillGoods;

class SeckillController extends BaseController
{
    public function index(Request $req,Seckill $seckill_model){
        $seckill_model = $seckill_model->orderBy('start_time','desc');
        $list = $seckill_model->paginate(20);
        return $this->success_msg('Success',$list);
    }

    public function add(Request $req,Seckill $seckill_model){
        if(!$req->isMethod('post')){
    		return $this->success_msg('Success',[]);
        }
        
    	$data = [
    		'name' => $req->name,
            'start_time'=> strtotime($req->seckill_date[0]),
            'end_time'=> strtotime($req->seckill_date[1]),
    	];

    	$seckill_model->insert($data);
    	return $this->success_msg();
    }

    public function edit(Request $req,Seckill $seckill_model,$id){
        if(!$req->isMethod('post')){
            $info = $seckill_model->find($id)->toArray();
            $info['seckill_date'] = [];
            $info['seckill_date'][0] = date('Y-m-d H:m',$info['start_time']);
            $info['seckill_date'][1] = date('Y-m-d H:m',$info['end_time']);
    		return $this->success_msg('Success',$info);
    	}

    	$data = [
    		'name' => $req->name,
            'start_time'=> strtotime($req->seckill_date[0]),
            'end_time'=> strtotime($req->seckill_date[1]),
    	];

    	$seckill_model->where('id',$id)->update($data);
    	return $this->success_msg();
    }

    public function del(Request $req,Seckill $seckill_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $seckill_model->destroy($ids);
        return $this->success_msg();
    }

    // 添加一个商品到商品秒杀
    public function add_seckill_goods(Request $req,SeckillGoods $seckill_goods_model){
        $id = $req->id;
        $seckill_id = $req->seckill_id;
        $user_info = auth()->user();
        $ids = explode(',',$id);
        $seckill_goods_list = $seckill_goods_model->where(['sid'=>$seckill_id,'user_id'=>$user_info['id']])->get()->toArray();

        $seckill_ids = [];
        foreach($seckill_goods_list as $v){
            $seckill_ids[] = $v['goods_id'];
        }

        foreach($ids as $v){
            if(in_array($v,$seckill_ids)){
                return $this->error_msg('有商品已经加入该秒杀活动!');
            }
        }

        $insertData = [];
        foreach($ids as $v){
            $insertData[] = ['sid'=>$seckill_id,'goods_id'=>$v,'user_id'=>$user_info['id']];
        }
        $seckill_goods_model->insert($insertData);

        return $this->success_msg();
    }

    // 获取参加的商品
    public function get_add_seckill_goods(Request $req,SeckillGoods $seckill_goods_model){
        $sid = $req->sid;
        $user_info = auth()->user();
        $goods_list = $seckill_goods_model->with('goods')->where('user_id',$user_info['id'])->where('sid',$sid)->paginate(20);
        return $this->success_msg('Success',$goods_list);
    }
}
