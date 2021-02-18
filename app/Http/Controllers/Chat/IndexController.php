<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\ChatMsgResource\ChatMsgCollection;
use App\Models\ChatFriend;
use App\Models\ChatMsg;
use App\Models\Store;
use App\Models\User;
use App\Services\UploadService;
use App\Services\UserService;
use Illuminate\Http\Request;
use \GatewayWorker\Lib\Gateway;

class IndexController extends Controller
{
    // 获取好友列表信息
    public function friends(ChatFriend $chat_friend_model,ChatMsg $chat_msg_model){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
  
        $data['uid'] = $user_info['id'];
        $data['friends'] = $chat_friend_model->with(['chat_msg'=>function($query){
            $query->orderBy('updated_at','desc'); // 加return
        },'store'=>function($query){
            $query->select('id','store_name','store_logo');
        }])->where('user_id',$user_info['id'])->paginate(20);

        return $this->success($data);
    }

    // 添加好友
    public function add_friend(Request $req,ChatFriend $chat_friend_model){
        $store_id = $req->store_id;
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();

        if(empty($store_id)){
            return $this->error('商家ID不能为空');
        }

        if($chat_friend_model->where('user_id',$user_info['id'])->where('store_id',$store_id)->exists()){
            return $this->error('两人已经是好友');
        }

        $data = [
            'user_id'       =>  $user_info['id'],
            'store_id'      =>  $store_id,
        ];

        $chat_friend_model->create($data);

        return $this->success();
        
    }

    // 获取聊天信息
    public function chat_msg(Request $req,ChatMsg $chat_msg_model){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        $store_id = $req->store_id; // 聊天对象ID

        // 将所有聊天显示已读
        $rs = $chat_msg_model->where('user_id',$user_info['id'])->where('store_id',$store_id)->update(['user_read'=>1]);

        // 告诉前端已经标记已读
        if($rs){
            GateWay::sendToUid($user_info['id'],json_encode(['type'=>'other']));
        }
        

        // 查询列表
        $data = $chat_msg_model->where('user_id',$user_info['id'])->where('store_id',$store_id)->with(['store'=>function($query){
            $query->select('id','store_name','store_logo');
        },'user'=>function($query){
            $query->select('id','nickname','avatar');
        }])->orderBy('id','desc')->paginate(20);

        return $this->success(new ChatMsgCollection($data) );
    }

    // 修改聊天信息为已读
    public function read_msg(Request $req,ChatMsg $chat_msg_model){
        $user_service = new UserService();
        $user_info = $user_service->getUserInfo();
        $store_id = $req->store_id; // 聊天对象ID
        $data = $chat_msg_model->where('store_id',$store_id)->where('user_id',$user_info['id'])->update(['user_read'=>1]);

        // 告诉前端已经标记已读
        if($data){
            GateWay::sendToUid($user_info['id'],json_encode(['type'=>'other']));
        }

        return $this->success($data);
    }


    // 接收聊天信息 框架和workman分离
    public function chat_event(Request $req){
        $data = $req->data;

        if(!is_array($data)){
            $data = json_decode($data,true);
        }

        // 存入数据库
        if($data['type'] != 'bind'){
            $chat_msg = [
                'type'          =>  $data['type'],
                'send_type'     =>  $data['send_type'],
                'user_id'       =>  $data['uid'],
                'store_id'      =>  $data['store_id'],
                'content'       =>  $data['content'],
            ];

            $uid = $data['uid'];

            // 进行数据判断
            try{
                // 插入数据库
                $chat_msg_model = new ChatMsg();
                $rs = $chat_msg_model->create($chat_msg);
                $uid = $chat_msg['user_id'];

                // 发送人的用户信息
                // $store_model = new Store();
                // $store_info = $store_model->select('id','store_logo','store_name')->where('id',$chat_msg['store_id'])->first();
                // $chat_msg['store_logo'] = $store_info['store_logo'];
                // $chat_msg['store_name'] = $store_info['store_name'];

                // 加上用户信息
                // $chat_msg['user_info'] = $user_info;

                $chat_msg_str = json_encode($chat_msg);

                // 推送给聊天二人 服务器已经接收并处理
                if($rs){
                    // 判断是否在线，如果在线则发送过去
                    if(!empty(Gateway::isUidOnline($uid))){
                        $rs = GateWay::sendToUid($uid,$chat_msg_str);
                    }
                    if(!empty(Gateway::isUidOnline($chat_msg['store_id']))){
                        GateWay::sendToUid($chat_msg['store_id'],$chat_msg_str);
                    }
                }else{
                    // 失败后发送给发送者
                    $chat_msg = ['type'=>'error'];
                    $chat_msg_str = json_encode($chat_msg);
                    GateWay::sendToUid($uid,$chat_msg_str);
                    return $this->error_msg('服务端存入失败');
                }

                return $this->success();
            }catch(\Exception $e){
                // 失败后发送给发送者
                $chat_msg = ['type'=>'error'];
                $chat_msg_str = json_encode($chat_msg);
                GateWay::sendToUid($uid,$chat_msg_str);
                return $this->error($e->getMessage());
            }

        }
    }


    public function image(){
        $uploads = new UploadService;
        $rs = $uploads->chat();
        if($rs['status']){
            return $this->success($rs['data'],$rs['msg']);
        }else{
            return $this->error($rs['msg']);
        }
    }
}
