<?php
namespace App\Qingwuit\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AutoService extends BaseService
{
    protected $config = [];
    public function __construct()
    {
        $this->config = $this->getService('Configs')->getFormatConfig('task');
    }
    
    /**
     * 定时任务
     *
     * @return void
     * @Description
     * @author hg <www.qwsystem.com>
     */
    public function autoTask()
    {
        try {
            $this->orderCancel(); // 取消
            $this->orderConfirm(); // 确认
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    // 取消订单
    public function orderCancel()
    {
        // 将订单状态为 1（未支付）时间在超过 配置时间的订单取消
        $order_list = $this->getService('Order', true)->where('order_status', 1)->where('created_at', '<', Carbon::parse($this->config['cancel'].' days ago')->toDateTimeString())->get(); //update(['order_status'=>0,'updated_at'=>now()])
        if ($order_list->isEmpty()) {
            return $this->formatError('order list is empty in autoService');
        }
        // $ids = [];
        foreach ($order_list as $v) {
            try {
                DB::beginTransaction();
                $rs = $this->getService('Order')->editOrderStatus($v['id'], 0, 'admin');
                if (!$rs['status']) {
                    throw new \Exception($v['id'].'-order_id error');
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }
        
        $this->format($rs);
    }

    // 自动确定订单 并评价
    public function orderConfirm()
    {
        // 将订单状态为 2 等待收货 3 确定收货 自动确认收货并评价
        $order_list = $this->getService('Order', true)->select('id','order_status')->whereIn('order_status', [3,4])->where('pay_time', '<', Carbon::parse($this->config['confirm'].' days ago')->toDateTimeString())->get();
        if ($order_list->isEmpty()) {
            return $this->formatError('order list is empty in autoService');
        }
        $ids = [];
        foreach ($order_list as $v) {
            $ids[] = $v['id'];
            if($v['order_status'] == 3 ) $this->getService('Order')->editOrderStatus($v['id'],4,'admins');
        }
        $rs = $this->getService('OrderComment')->systemAdd($ids);
        return $rs['status']?$this->format($rs['data']):$this->formatError($rs['msg']);
    }

    // 自动结算
    public function orderSettlement()
    {
        $os_service = new OrderSettlementService();
        $rs = $os_service->add();
        return $rs['status']?$this->format($rs['data']):$this->formatError($rs['msg']);
    }
}
