<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\ChatFriendResource\ChatFriendCollection;
use App\Http\Resources\Chat\ChatMsgResource\SellerChatMsgCollection;
use App\Models\ChatFriend;
use App\Models\ChatMsg;
use App\Models\User;
use Illuminate\Http\Request;
use \GatewayWorker\Lib\Gateway;

class SellerChatController extends Controller
{
    // 获取好友列表信息
    public function friends(ChatFriend $chat_friend_model,ChatMsg $chat_msg_model){
 
        
        $store_id = $this->get_store(true);
        $data = new ChatFriendCollection($chat_friend_model->with(['user'=>function($query){
            $query->select('id','nickname','avatar');
        }])->where('store_id',$store_id)->paginate(20)) ;

        return $this->success($data);
    }

    // 获取聊天信息
    public function chat_msg(Request $req,ChatMsg $chat_msg_model){

        $uid = $req->uid; // 聊天对象ID
        $store_id = $this->get_store(true);

        // 将所有聊天显示已读
        $rs = $chat_msg_model->where('user_id',$uid)->where('store_id',$store_id)->update(['store_read'=>1]);

        // 告诉前端已经标记已读
        if($rs){
            GateWay::sendToUid($store_id,json_encode(['type'=>'other']));
        }
        

        // 查询列表
        $data = $chat_msg_model->where('user_id',$uid)->where('store_id',$store_id)->with(['store'=>function($query){
            $query->select('id','store_name','store_logo');
        },'user'=>function($query){
            $query->select('id','nickname','avatar');
        }])->orderBy('id','desc')->paginate(20);

        return $this->success(new SellerChatMsgCollection($data) );
    }

    // 修改聊天信息为已读
    public function read_msg(Request $req,ChatMsg $chat_msg_model){
        $store_id = $this->get_store(true);
        $uid = $req->uid; // 聊天对象ID

        $data = $chat_msg_model->where('store_id',$store_id)->where('user_id',$uid)->update(['store_read'=>1]);

        // 告诉前端已经标记已读
        if($data){
            GateWay::sendToUid($store_id,json_encode(['type'=>'other']));
        }

        return $this->success($data);
    }


    // 接收聊天信息 框架和workman分离
    public function chat_event(Request $req){
        $data = $req->data;
        $store_id = $this->get_store(true);

        // 存入数据库
        if($data['type'] != 'bind'){
            $chat_msg = [
                'type'          =>  $data['type'],
                'send_type'     =>  $data['send_type'],
                'user_id'       =>  $data['uid'],
                'store_id'      =>  $store_id,
                'content'       =>  $data['content'],
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
                // $user_model = new User();
                // $user_info = $user_model->select('id','nickname','avatar')->where('id',$uid)->first();
                // $chat_msg['nickname'] = $user_info['nickname'];
                // $chat_msg['avatar'] = $user_info['avatar'];

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
                    GateWay::sendToUid($chat_msg['store_id'],$chat_msg_str);
                    return $this->error('服务端存入失败');
                }

                return $this->success();
            }catch(\Exception $e){
                // 失败后发送给发送者
                $chat_msg = ['type'=>'error'];
                $chat_msg_str = json_encode($chat_msg);
                GateWay::sendToUid($chat_msg['store_id'],$chat_msg_str);
                return $this->error($e->getMessage());
            }

        }
    }


    
}
