<?php

namespace App\Qingwuit\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSettlementService extends BaseService
{
    /**
     * 订单结算
     *
     * @param boolean $auto 系统处理 | 手动处理  结算数量大可以使用队列来跑
     * @return void
     * @Description
     * @author hg <www.qingwuit.com>
     */
    public function add($auto = true)
    {
        $order_model = $this->getService('Order', true);

        $dateString = Carbon::parse('30 days ago')->toDateString(); // 30天前的数据 最多可结算
        $now = now();
        $order_status = 6; // 订单完成状态
        $settlement_no = date('YmdHis') . mt_rand(1000, 9999); // 此处操作结算订单号

        $order_list = $order_model->whereDate('pay_time', '>', $dateString)
            ->where('order_status', $order_status)
            ->where('is_settlement', 0) // 未结算的
            ->with(['order_goods:order_id,goods_id,buy_num', 'distribution:id,order_id,commission', 'refund:id,order_id,refund_type,refund_verify']) // 获取 商品信息 | 分销信息 | 售后信息
            ->get();

        // 结算订单为空
        if ($order_list->isEmpty()) return $this->formatError(__('tip.order.empty'));

        $distribution_order = []; // 分销订单ID
        $order_settlement_array = []; // 结算信息
        $store_list = []; // 应该结算总金额
        $order_goods_list = []; // 所有订单商品信息;
        foreach ($order_list as $v) {

            // 判断是否是售后订单 // 订单为换货的才能结算，退款的不予结算 | 退款状态 处理中不予结算
            if (!empty($v->refund) && ($v->refund->refund_type == 0 && $v->refund->refund_verify > 0)) continue;
            $item = [];
            $item['order_id'] = $v->id;
            $item['user_id'] = $v->user_id;
            $item['store_id'] = $v->store_id;
            $item['settlement_no'] = $settlement_no;
            $item['total_price'] = $v->total_price; // 订单金额
            $item['settlement_price'] = $v->total_price; // 结算金额
            $item['status'] = 1; // 结算状态，暂时不知道什么用先全默认1
            $item['info'] = $auto ? __('tip.order.orderSettlement') : __('tip.order.orderSettlementHandle'); // 备注信息
            $item['created_at'] = $now;
            $item['updated_at'] = $now;

            // 如果订单存在分销的情况
            if (!empty($v->distribution)) {
                $distribution_order[] = $v->id;

                // 结算金额减去分销的金额
                $commission = 0;
                foreach ($v->distribution as $vo) {
                    $commission += $vo['commission'];
                }
                $item['settlement_price'] -= $commission;
                $item['info'] .= '|' . __('tip.order.goodsCommission') . '-' . $commission;
            }

            // 如果order_goods 不为空 统计每个商品成功售卖的数量
            if (!empty($v->order_goods)) {
                foreach ($v->order_goods as $vo) {
                    if (isset($order_goods_list[$vo['goods_id']])) {
                        $order_goods_list[$vo['goods_id']] += $vo['buy_num'];
                    } else {
                        $order_goods_list[$vo['goods_id']] = $vo['buy_num'];
                    }
                }
            }

            // 商家账号应该返回金额的统计
            if (!isset($store_list[$v->store_id])) {
                $store_list[$v->store_id] = $item['settlement_price'];
            } else {
                $store_list[$v->store_id] = round(bcadd($store_list[$v->store_id], $item['settlement_price']), 2);
            }

            $order_settlement_array[] = $item;
        }

        // 循环进行插入 销售量
        // foreach($order_goods_list as $k=>$v){
        //     $this->getService('Goods',true)->where('id',$k)->increment('goods_sale',$v);
        // }

        try {
            DB::beginTransaction();

            // 数据库处理日志
            $os_model = $this->getService('OrderSettlement', true);
            $dis_service = $this->getService('Distribution');
            $os_model->insert($order_settlement_array); // 插入结算日志数据库
            $dis_service->handleSettlement($distribution_order); // 处理分销

            // 商家金额处理
            foreach ($store_list as $k => $v) {
                $this->getService('MoneyLog')->edit([
                    'name'  =>  __('tip.order.orderSettlement'),
                    'user_id'   =>  $k,
                    'is_belong'   =>  1,
                    'money'   =>  $v,
                ]);
            }

            // 订单修改状态为已经结算
            $this->getService('Order', true)->whereIn('id', $distribution_order)->update(['is_settlement' => 1]);

            DB::commit();
            return $this->format([]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->formatError($e->getMessage());
        }
    }
}
