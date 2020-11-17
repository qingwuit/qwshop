<?php
namespace App\Services;

use App\Http\Resources\Home\CollectiveLogResourcee\CollectiveLogGoodsCollection;
use App\Models\Collective;
use App\Models\CollectiveLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CollectiveService extends BaseService{
    // 根据商品ID 获取拼团 详情
    public function getCollectiveInfoByGoodsId($goods_id){
        $collective_model = new Collective();
        $info = $collective_model->where('goods_id',$goods_id)->first();

        if(!$info){
            return $this->format_error('collective empty');
        }

        return $this->format($info);
    }

    // 根据商品ID获取当前的正在操作的团
    public function getCollectiveLogByGoodsId($goods_id){
        $collective_log_model = new CollectiveLog();
        $list = $collective_log_model->where('goods_id',$goods_id)->where('status',2)->withCount('orders')->with('user')->get();
        return $this->success(new CollectiveLogGoodsCollection( $list));
    }

    // 根据ID 和订单ID创建日志 collective_id 是日志ID collective_resp 团信息 order_goods 订单商品信息
    public function createCollectiveLog($collective_id,$collective_resp,$order_goods){
        try{
            DB::beginTransaction();
            if($collective_id>0){
                $collective_log_model = new CollectiveLog();
                $cli = $collective_log_model->where('id',$collective_id)->withCount('orders')->first();
                if($cli->orders_count>=$cli->need){ // 满了
                    throw new \Exception(__('markets.collective_is_full'));
                }
                if($cli->orders_count+1 == $cli->need){
                    $collective_log_model->status=1;
                    $collective_log_model->save();
                }
            }
            if($collective_id<0){
                $collective_log_model = new CollectiveLog();
                $data = [
                    'user_id' => $order_goods['user_id'],
                    'store_id' => $order_goods['store_id'],
                    'goods_id' => $order_goods['goods_id'],
                    'collective_id' => $collective_resp['data']['id'],
                    'discount' => $collective_resp['data']['discount'],
                    'need' => $collective_resp['data']['need'],
                    'status' => 2,
                ];
                $collective_log_info = $collective_log_model->create($data);
                $collective_id = $collective_log_info->id;
            }
            DB::commit();
            return $collective_id;
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        
    }
}
