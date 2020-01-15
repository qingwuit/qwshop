<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Support\Facades\Hash;
use App\Models\Favorites;
use App\Models\Order;
use App\Models\SmsLog;
use App\Models\UserCkeck;
use App\Models\Users;
use App\Tools\Uploads;
use App\Tools\Helper;

class UsersController extends BaseController
{
    // 用户中心首页默认信息
    public function get_user_default(Order $order_model,Favorites $fav_model){
        $user_info = auth()->user(); // 用户信息
        unset($user_info['password']);
        $data['user_info'] = $user_info;
        $data['order_num']['no_pay'] = $order_model->where('user_id',$user_info['id'])->where(get_order_params('NOPAY'))->count(); 
        $data['order_num']['wait_send'] = $order_model->where('user_id',$user_info['id'])->where(get_order_params('WAITSEND'))->count(); 
        $data['order_num']['wait_rec'] = $order_model->where('user_id',$user_info['id'])->where(get_order_params('WAITREC'))->count(); 
        $data['order_num']['wait_comment'] = $order_model->where('user_id',$user_info['id'])->where(get_order_params('WAITCOMMENT'))->count(); 
        $data['order_num']['refund'] = $order_model->where('user_id',$user_info['id'])->where(get_order_params('REFUND'))->count(); 
        $order_list = $order_model->where('user_id',$user_info['id'])->take(10)->orderBy('id','desc')->get()->toArray();
        $store_list = [];
        foreach($order_list as $v){
            $store_list[$v['store_id']][] = $v;
        }
        $data['store_list'] = array_merge($store_list);
        $fav_list = $fav_model->where('user_id',$user_info['id'])->where('is_type',0)->with(['goods','spec_once'])->take(10)->get()->toArray();
        foreach($fav_list as $k=>$v){
            if(!empty($v['spec_once'])){
                $v['goods']['goods_price'] = $v['spec_once']['goods_price'];
            }
            $v['goods']['image'] = get_format_image($v['goods']['goods_master_image'],200);
            $fav_list[$k] = $v;
        }
        $data['fav_list'] = $fav_list;
        
        return $this->success_msg('ok',$data);
    }

    // 获取订单信息
    public function get_user_order(Request $req,Order $order_model,Config $config_model){
        $user_info = auth()->user(); // 用户信息
        $order_model2 = $order_model;
        if(isset($req->order_no) && !empty($req->order_no)){
            $order_model = $order_model->where('order_no',$req->order_no);
        }
        if(isset($req->order_type) && !empty($req->order_type)){
            $order_model = $order_model->where(get_order_params($req->order_type));
        }

        $data['order_list'] = $order_model->where('user_id',$user_info['id'])->with('order_goods')->orderBy('id','desc')->paginate(20)->toArray();
        $data['order_list']['data'] = get_order_status($data['order_list']['data']);
        $data['order_num']['all'] = $order_model2->where('user_id',$user_info['id'])->count(); 
        $data['order_num']['no_pay'] = $order_model2->where('user_id',$user_info['id'])->where(get_order_params('NOPAY'))->count(); 
        $data['order_num']['wait_send'] = $order_model2->where('user_id',$user_info['id'])->where(get_order_params('WAITSEND'))->count(); 
        $data['order_num']['wait_rec'] = $order_model2->where('user_id',$user_info['id'])->where(get_order_params('WAITREC'))->count(); 
        $data['order_num']['wait_comment'] = $order_model2->where('user_id',$user_info['id'])->where(get_order_params('WAITCOMMENT'))->count(); 
        $data['order_num']['refund'] = $order_model2->where('user_id',$user_info['id'])->where(get_order_params('REFUND'))->count(); 

        return $this->success_msg('ok',$data);
    }

    // 获取物流信息
    public function get_user_delivery(Request $req){
        $delivery_no = $req->delivery_no??'';
        $helper_model = new Helper();
        $delivery_list = [];

        if(!empty($delivery_no)){
            $delivery_list = json_decode($helper_model->get_freight_info($delivery_no),true);
        }
        return $this->success_msg('ok',$delivery_list);
    }

    // 确定收货
    public function change_order_status(Request $req,Order $order_model){
        $order_id = $req->order_id??0;
        $user_info = auth()->user();
        $order_model->complete_order($order_id,$user_info['id']);
        return $this->success_msg();
    }

    // 是否收藏
    public function is_fav(Request $req,Favorites $fav_model){
        $user_info = auth()->user(); // 用户信息
        $mix_id = $req->mix_id;
        $is_type = $req->is_type;
        $info = $fav_model->where('user_id',$user_info['id'])->where('mix_id',$mix_id)->where('is_type',$is_type)->count();
        if(empty($info)){
            return $this->error_msg('未收藏');
        }else{
            return $this->success_msg('已收藏');
        }
    }

    // 修改收藏
    public function edit_fav(Request $req,Favorites $fav_model){
        $user_info = auth()->user(); // 用户信息
        $mix_id = $req->mix_id;
        $is_type = $req->is_type;
        $info = $fav_model->where('user_id',$user_info['id'])->where('mix_id',$mix_id)->where('is_type',$is_type)->count();
        if(empty($info)){
            $fav_model->insert(['mix_id'=>$mix_id,'is_type'=>$is_type,'user_id'=>$user_info['id']]);
            return $this->success_msg('ok',1);  // 代表收藏
        }else{
            $fav_model->where(['mix_id'=>$mix_id,'is_type'=>$is_type,'user_id'=>$user_info['id']])->delete();
            return $this->success_msg('ok',0); // 代表删除
        }

        
    }

    // 获取用户信息
    public function get_user_info(){
        $user_info = auth()->user();
        unset($user_info['password']);
        unset($user_info['pay_password']);
        return $this->success_msg('ok',$user_info);
    }

    // 修改用户信息
    public function edit_user_info(Request $req,Users $user_model,SmsLog $sms_log_model){
        $user_info = auth()->user();
        $password = $user_info['password'];
        $pay_password = $user_info['pay_password'];
        unset($user_info['password']);
        unset($user_info['pay_password']);
        if(!$req->isMethod('post')){
            return $this->success_msg('ok',$user_info);
        }

        $data = [];

        if(isset($req->nickname)){
            $data['nickname'] = $req->nickname;
        }

        if(isset($req->avatar)){
            $data['avatar'] = $req->avatar;
        }

        if(isset($req->gender)){
            $data['gender'] = $req->gender;
        }

        if(isset($req->email)){
            $data['email'] = $req->email;
        }

        // 手机修改需要手机号码和验证码
        if(isset($req->phone) && isset($req->code)){
            $sms_log = $sms_log_model->where(['code'=>json_encode(['code'=>intVal($req->code)]),'phone'=>$req->phone,'is_type'=>3])->orderBy('id','desc')->where('add_time','>',time()-300)->first();
            if(empty($sms_log)){
                return $this->error_msg('验证码错误');
            }
            if($sms_log['is_use'] > 0){
                return $this->error_msg('短信验证码已经失效请重新生成！');
            }
            $sms_log_model->where(['code'=>json_encode(['code'=>intVal($req->code)]),'phone'=>$req->phone,'is_type'=>3])->orderBy('id','desc')->where('add_time','>',time()-300)->update(['is_use'=>1]);
            $data['phone'] = $req->phone;
        }

        // 手机修改需要手机号码和验证码
        if(isset($req->email) && isset($req->code)){
            $sms_log = $sms_log_model->where(['code'=>json_encode(['code'=>intVal($req->code)]),'phone'=>$req->email,'is_type'=>4])->orderBy('id','desc')->where('add_time','>',time()-300)->first();
            if(empty($sms_log)){
                return $this->error_msg('验证码错误');
            }
            if($sms_log['is_use'] > 0){
                return $this->error_msg('短信验证码已经失效请重新生成！');
            }
            $sms_log_model->where(['code'=>json_encode(['code'=>intVal($req->code)]),'phone'=>$req->email,'is_type'=>4])->orderBy('id','desc')->where('add_time','>',time()-300)->update(['is_use'=>1]);
            $data['email'] = $req->email;
        }

        if(isset($req->old_password) && isset($req->password)){
            if(!Hash::check($req->old_password,$password)){
                return $this->error_msg('旧密码错误');
            }
            $data['password'] = Hash::make($req->password);
        }

        if(isset($req->pay_password) && isset($req->old_pay_password)){
            $old_pay_password = intval($req->old_pay_password);
            if($pay_password != $req->old_pay_password){
                return $this->error_msg('旧密码错误');
            }
            
            $data['pay_password'] = $req->pay_password;
        }

        
        $rs = $user_model->where('id',$user_info['id'])->update($data);
        return $this->success_msg('ok',$rs);
    }

    // 获取用户认证信息
    public function get_user_check_info(UserCkeck $user_ckeck_model){
        $user_info = auth()->user();
        $check_info = $user_ckeck_model->where('user_id',$user_info['id'])->first();
        if(empty($check_info)){
            return $this->error_msg('未能认证');
        }else{
            return $this->success_msg('ok',$check_info);
        }
    }

    // 获取修改认证信息
    public function edit_user_check_info(Request $req,UserCkeck $user_ckeck_model){
        $user_info = auth()->user();
        $check_info = $user_ckeck_model->where('user_id',$user_info['id'])->first();

        if(!$req->isMethod('post')){
            return $this->success_msg('ok',$check_info);
        }
        $data = [
            'user_id'       =>  $user_info['id'],
            'real_name'     =>  $req->real_name,
            'card_no'       =>  $req->card_no,
            'card_top'      =>  $req->card_top,
            'card_bottom'   =>  $req->card_bottom,
            'bank_card'     =>  $req->bank_card,
            'bank_name'     =>  $req->bank_name,
        ];

        if(empty($check_info)){
            $user_ckeck_model->where('user_id',$user_info['id'])->insert($data);
        }else{
            $user_ckeck_model->where('user_id',$user_info['id'])->update($data);
        }
        
        return $this->success_msg('ok');
    }

    // 获取用户收藏关注数据
    public function get_fav_list(Request $req,Favorites $favorites_model){
        $type = $req->is_type??0;
        $user_info = auth()->user();
        $favorites_model = $favorites_model->where('user_id',$user_info['id'])->where('is_type',$type);
        if(empty($type)){
            $favorites_model = $favorites_model->with('goods');
        }else{
            $favorites_model = $favorites_model->with('store');
        }
        $list = $favorites_model->paginate(20);
        return $this->success_msg('ok',$list);
    }

    // 上传用户收藏关注数据
    public function del_fav(Request $req,Favorites $favorites_model){
        $id = $req->id;
        $ids = explode(',',$id);
        $favorites_model->destroy($ids);
        return $this->success_msg();
    }

    public function avatar(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['is_thumb'=>1,'width'=>100,'height'=>100,'filepath'=>'avatar/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    // 身份证上传
    public function user_card(){
        $uploads = new Uploads;
        $rs = $uploads->uploads(['is_thumb'=>1,'filepath'=>'user_check/']);
        if($rs['status']){
            return $this->success_msg('Success',$rs['path']);
        }else{
            return $this->error_msg($rs['msg']);
        }
    }

    // 验证支付密码
    public function check_pay_password(Request $req){
        $user_info = auth()->user();
        $pwd = $req->pay_password;

        if($user_info['pay_password'] != $pwd){
            return $this->error_msg('支付密码错误');
        }else{
            return $this->success_msg('支付密码正确');
        }
    }

    // 获取邀请注册链接
    public function get_inviter_info(){
        $helper_model = new Helper();
        $config_model = new Config(); 
        $config_info = $config_model->get_format_config();
        $user_info = auth()->user();
        $data['qrcode'] = $config_info['web_url'].$helper_model->create_qrcode($user_info['id'],'inviter/'.$user_info['id'].'/','qrcode');
        $baseUrl = public_path('/Common/poster_bg.png');
        $qrUrl = public_path('/Uploads/inviter/'.$user_info['id'].'/qrcode.png');
        $data['poster'] = $config_info['web_url'].$helper_model->create_poster($baseUrl,$qrUrl,114,185,$user_info['id']);
        $data['user_id'] = $user_info['id'];
        return $this->success_msg('ok',$data);
    }
}
