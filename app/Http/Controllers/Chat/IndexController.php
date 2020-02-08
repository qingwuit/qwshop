<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Home\BaseController;
use App\Models\ChatFriend;
use App\Models\ChatMsg;
use App\Models\Users;
use \GatewayWorker\Lib\Gateway;

class IndexController extends BaseController
{
    // 获取好友列表信息
    public function get_chat_friend(Request $req,ChatFriend $chat_friend_model){
        $user_info = auth()->user();
        unset($user_info['password']);
        unset($user_info['pay_password']);
        $data['user_info'] = $user_info;
        $data['chat_friend'] = $chat_friend_model->with(['chat_msg'=>function($query){
            $query->orderBy('add_time','desc'); // 加return
        },'friend_info'=>function($query){
            $query->select('id','nickname','avatar');
        }])->withCount(['not_read'=>function($query){
            $query->where('is_read',0);
        }])->where('user_id',$user_info['id'])->paginate(20);

        return $this->success_msg('ok',$data);
    }

    // 添加好友
    public function add_friend(Request $req,ChatFriend $chat_friend_model){
        $friend_id = $req->firend_id;
        $user_info = auth()->user();

        if($chat_friend_model->where('user_id',$user_info['id'])->where('to_user_id',$friend_id)->exists() && $chat_friend_model->where('to_user_id',$user_info['id'])->where('user_id',$friend_id)->exists()){
            return $this->success_msg('两人已经是好友');
        }

        if($friend_id == $user_info['id']){
            return $this->success_msg('不能加自己好友');
        }

        $data[] = [
            'user_id'       =>  $user_info['id'],
            'to_user_id'    =>  $friend_id,
            'add_time'      =>  time(),
        ];

        $data[] = [
            'user_id'       =>  $friend_id,
            'to_user_id'    =>  $user_info['id'],
            'add_time'      =>  time(),
        ];

        $chat_friend_model->insert($data);

        return $this->success_msg('ok');
        
    }

    // 获取聊天信息
    public function get_chat_msg(Request $req,ChatMsg $chat_msg_model){
        $user_info = auth()->user();
        $to_user_id = $req->to_user_id; // 聊天对象ID
        $data = $chat_msg_model->orWhere(function($query) use($user_info,$to_user_id){
            $query->where('user_id',$user_info['id'])->where('to_user_id',$to_user_id);
        })->orWhere(function($query) use($user_info,$to_user_id){
            $query->where('user_id',$to_user_id)->where('to_user_id',$user_info['id']);
        })->with(['user_info'=>function($query){
            $query->select('id','nickname','avatar');
        }])->orderBy('add_time','desc')->paginate(20);

        return $this->success_msg('ok',$data);
    }


    // 接收聊天信息 框架和workman分离
    public function chat_event(Request $req){
        $data = $req->data;

        // 存入数据库
        if($data['type'] != 'bind'){
            $chat_msg = [
                'type'          =>  $data['type'],
                'user_id'       =>  $data['content']['user_id'],
                'to_user_id'    =>  $data['content']['to_user_id'],
                'content'       =>  $data['content']['content'],
                'add_time'      =>  time(),
            ];

            // 进行数据判断
            try{
                // 插入数据库
                $chat_msg_model = new ChatMsg();
                $rs = $chat_msg_model->insert($chat_msg);

                // 发送人的用户信息
                $user_model = new Users();
                $user_info = $user_model->select('id','nickname','avatar')->where('id',$chat_msg['user_id'])->first();

                // 加上用户信息
                $chat_msg['user_info'] = $user_info;

                $chat_msg_str = json_encode($chat_msg);

                // 推送给聊天二人 服务器已经接收并处理
                if($rs){
                    // 判断是否在线，如果在线则发送过去
                    if(!empty(Gateway::isUidOnline($chat_msg['user_id']))){
                        $rs = GateWay::sendToUid($chat_msg['user_id'],$chat_msg_str);
                    }
                    if(!empty(Gateway::isUidOnline($chat_msg['to_user_id']))){
                        GateWay::sendToUid($chat_msg['to_user_id'],$chat_msg_str);
                    }
                }else{
                    // 失败后发送给发送者
                    $chat_msg = ['type'=>'error'];
                    $chat_msg_str = json_encode($chat_msg);
                    GateWay::sendToUid($chat_msg['user_id'],$chat_msg_str);
                    return $this->error_msg('服务端存入失败');
                }

                return $this->success_msg('ok');
            }catch(\Exception $e){
                // 失败后发送给发送者
                $chat_msg = ['type'=>'error'];
                $chat_msg_str = json_encode($chat_msg);
                GateWay::sendToUid($chat_msg['user_id'],$chat_msg_str);
                return $this->error_msg($e->getMessage());
            }

        }
    }


}