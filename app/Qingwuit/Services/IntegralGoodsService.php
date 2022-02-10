<?php
namespace App\Qingwuit\Services;

use Illuminate\Support\Facades\DB;

class IntegralGoodsService extends BaseService
{
    public function search()
    {
        $ig_model = $this->getService('IntegralGoods', true);
        $params = request()->params??'';
        try {
            if (!empty($params)) {
                $params_array = json_decode(base64_decode($params), true);
    
                // 栏目
                if (isset($params_array['cid']) && !empty($params_array['cid'])) {
                    $ig_model = $ig_model->where('cid', $params_array['cid']);
                }

                // 排序
                if (isset($params_array['sort_type']) && !empty($params_array['sort_type'])) {
                    $ig_model = $ig_model->orderBy($params_array['sort_type'], $params_array['sort_order']);
                } else {
                    $ig_model = $ig_model->orderBy('id', 'desc')->orderBy('goods_sale', 'desc');
                }
            }
    
            $list = $ig_model->where('goods_status', 1)
                        ->paginate(request()->per_page??30);
        } catch (\Exception $e) {
            return $this->formatError(__('tip.error'));
        }
        return $this->format($list);
    }

    // 积分订单建立并支付
    public function createOrder()
    {
        $params = request()->all();
        
        // 地址验证
        $address_resp = $this->getService('Order')->checkAddress();
        if (!$address_resp['status']) {
            return $this->formatError($address_resp['msg']);
        }
        $address_info = $address_resp['data'];

        $userInfo = $this->getUser('users')['data'];

        $goods_info = $this->getService('IntegralGoods', true)->find($params['id']);

        $total_price = $goods_info['goods_price']*$params['buy_num'];

        if ($userInfo['integral']<$total_price) {
            return $this->formatError(__('tip.order.moneyNotEnough'));
        }

        $make_rand = date('YmdHis').$userInfo['id'].mt_rand(1000, 9999); // 生成订单号
        try {
            DB::beginTransaction();
            $order_data = [
                'order_no'                  =>  $make_rand, // 订单号
                'user_id'                   =>  $userInfo['id'], // 用户ID
                'order_name'                =>  $goods_info['goods_name'], // 商品ID
                'order_image'               =>  $this->getService('Tool')->thumb($goods_info['goods_master_image'], 150), // 商品图片
                'receive_name'              =>  $address_info['receive_name'], // 收件人姓名
                'receive_tel'               =>  $address_info['receive_tel'], // 收件人电话
                'receive_area'              =>  $address_info['area_info'], // 收件人地区
                'receive_address'           =>  $address_info['address'], // 详细地址
                'total_price'               =>  $total_price, //
                'order_price'               =>  $total_price, //
                'order_status'              =>  2, //
                'remark'                    =>  request()->remark??'', // 备注
            ];
    
            $order_info = $this->getService('IntegralOrder', true)->create($order_data); // 订单数据插入数据库
    
            $order_goods_data = [
                'order_id'      =>$order_info->id, // 订单ID
                'user_id'       =>$order_data['user_id'], // 用户ID
                'goods_id'      =>$goods_info['id'], // 商品id
                'goods_name'    =>$order_data['order_name'], // 商品名称
                'goods_image'   =>$order_data['order_image'], // 商品图片
                'buy_num'       =>$params['buy_num'], // 购买数量
                'goods_price'   =>$goods_info['goods_price'], // 商品价格
                'total_price'   =>$goods_info['goods_price']*$params['buy_num'], // 总价格
            ];
    
            $this->getService('IntegralOrderGoods', true)->create($order_goods_data); // 插入订单商品表

            // __('users.money_log_order'),$userInfo['id'],-$total_price,2
            $this->getService('MoneyLog')->edit(
                ['name'=>'积分订单','money'=>-$total_price,'is_type'=>2]
            );
            $this->getService('IntegralGoods', true)->where('id', $params['id'])->decrement('goods_stock', $params['buy_num']);
            DB::commit();

            $resp_data['order_id'][] = $order_info->id;
            $resp_data['order_no'][] = $make_rand;
            return $this->format($resp_data);
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }
    }
}
