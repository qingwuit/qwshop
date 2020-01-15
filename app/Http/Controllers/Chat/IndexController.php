<?php

namespace App\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseController;
use App\Models\ChatFriend;
use Illuminate\Support\Facades\DB;

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
        }])->withCount(['not_read'=>function($query){
            $query->where('is_read',0);
        }])->where('user_id',$user_info['id'])->paginate(20);

        return $data;
    }

    // 添加好友
    public function add_friend(Request $req,ChatFriend $chat_friend_model){
        $friend_id = $req->firend_id;
        $user_info = auth()->user();

        if($chat_friend_model->where('user_id',$user_info['id'])->where('to_user_id',$friend_id)->exists() && $chat_friend_model->where('to_user_id',$user_info['id'])->where('user_id',$friend_id)->exists()){
            return $this->success_msg('两人已经是好友');
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

        $chat_friend_model->insetAll($data);

        return $this->success_msg('ok');
        
    }

}