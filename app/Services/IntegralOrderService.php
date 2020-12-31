<?php
namespace App\Services;

use App\Models\IntegralGoods;
use App\Models\IntegralOrder;
use App\Models\IntegralOrderGoods;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\DB;

class IntegralOrderService extends BaseService{
    
    use HelperTrait;

    // 积分订单建立并支付
    public function createOrder(){
        $order_service = new OrderService();
        $igm = new IntegralGoods();
        $iom = new IntegralOrder();
        $iogm = new IntegralOrderGoods();
        $ml_service = new MoneyLogService();
  
        $params = request()->all();
        
        // 地址验证
        $address_resp = $order_service->checkAddress();
        if(!$address_resp['status']){
            return $this->format_error($address_resp['msg']);
        }
        $address_info = $address_resp['data'];

        $user_service = new UserService;
        $user_info = $user_service->getUserInfo();
        if(empty($user_info)){
            return $this->format_error(__('base.error').' - order_service');
        }

        $goods_info = $igm->find($params['id']);

        $total_price = $goods_info['goods_price']*$params['buy_num'];

        if($user_info['integral']<$total_price){
            return $this->format_error(__('users.integral_not_enough'));
        }

        $make_rand = date('YmdHis').$user_info['id'].mt_rand(1000,9999); // 生成订单号
        try{
            DB::beginTransaction();
            $order_data = [
                'order_no'                  =>  $make_rand, // 订单号
                'user_id'                   =>  $user_info['id'], // 用户ID
                'order_name'                =>  $goods_info['goods_name'], // 商品ID
                'order_image'               =>  $this->thumb($goods_info['goods_master_image'],150), // 商品图片
                'receive_name'              =>  $address_info['receive_name'], // 收件人姓名
                'receive_tel'               =>  $address_info['receive_tel'], // 收件人电话
                'receive_area'              =>  $address_info['area_info'], // 收件人地区
                'receive_address'           =>  $address_info['address'], // 详细地址
                'total_price'               =>  $total_price, // 
                'order_price'               =>  $total_price, // 
                'order_status'              =>  2, // 
                'remark'                    =>  request()->remark??'', // 备注
            ];
    
            $order_info = $iom->create($order_data); // 订单数据插入数据库
    
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
    
            $iogm->create($order_goods_data); // 插入订单商品表
            $ml_service->editMoney(__('users.money_log_order'),$user_info['id'],-$total_price,2);
            $igm->where('id',$params['id'])->decrement('goods_stock',$params['buy_num']);
            DB::commit();

            $resp_data['order_id'][] = $order_info->id;
            $resp_data['order_no'][] = $make_rand;
            return $this->format($resp_data);
        }catch(\Exception $e){
            return $this->format_error($e->getMessage());
        }

    }

    // 获取订单
    public function getOrders($type="user"){
        $order_model = new IntegralOrder();

        if($type == 'user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            $order_model = $order_model->where('user_id',$user_info['id']);
        }
        
        $order_model = $order_model->with(['user'=>function($q){
            return $q->select('id','username');
        },'order_goods']);
        
        // 订单号
        $order_no  = request()->order_no;
        if(!empty($order_no)){
            $order_model = $order_model->where('order_no','like','%'.$order_no.'%');
        }

        // 用户ID
        $user_id = request()->user_id;
        if(!empty($user_id)){
            $order_model = $order_model->where('user_id',$user_id);
        }

        // 下单时间
        $created_at = request()->created_at;
        if(!empty($created_at)){
            $order_model = $order_model->whereBetween('created_at',[$created_at[0],$created_at[1]]);
        }
        
        // 订单状态
        if(isset(request()->order_status)){
            $order_model = $order_model->where('order_status',request()->order_status);
        }

 
        $order_model = $order_model->orderBy('id','desc')
                    ->paginate(request()->per_page??30);
        return $this->format($order_model);
    }

    // 获取订单信息通过订单ID 默认是需要用用户
    public function getOrderInfoById($id,$auth='user'){
        $order_model = new IntegralOrder();

        if($auth=='user'){
            $user_service = new UserService;
            $user_info = $user_service->getUserInfo();
            $order_model = $order_model->where('user_id',$user_info['id']);
        }

        $order_info = $order_model->with('order_goods')->where('id',$id)->first();
        return $this->format($order_info);
    }

}
