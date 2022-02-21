<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatFriendCollection;
use Illuminate\Http\Request;
use Workerman\Worker;

class IndexController extends Controller
{
    // 获取聊天好友列表
    public function friends(Request $request)
    {
        $provider = $request->provider ?? 'anonymous'; // admins | users
        $ip = $request->getClientIp();
        $id = '';
        if ($provider != 'anonymous') {
            try {
                $id = $this->getUserId($provider);
            } catch (\Exception $e) {
                $id = md5($ip);
            }
        } else {
            $id = md5($ip);
        }

        $friends = $this->getService('ChatFriend', true)->orWhere(function ($q) use ($provider, $id) {
            return $q->where('sid', $id)->where('stype', $provider);
        })->orWhere(function ($q) use ($provider, $id) {
            return $q->where('rid', $id)->where('rtype', $provider);
        })->orderBy('updated_at', 'desc')->get();

        return $this->success(new ChatFriendCollection($friends));
    }

    // 获取指定好友的聊天内容
    public function friend_content(Request $request)
    {
        $provider = $request->provider ?? 'anonymous'; // admins | users
        $ip = $request->getClientIp();
        $id = '';
        $rid = $request->rid; // 对方Id
        $rtype = $request->rtype; // 对方类型
        if ($provider != 'anonymous') {
            try {
                $id = $this->getUserId($provider);
            } catch (\Exception $e) {
                $id = md5($ip);
            }
        } else {
            $id = md5($ip);
        }

        $data = $this->getService('ChatContent', true)->orWhere(function ($q) use ($provider, $id, $rid, $rtype) {
            return $q->where('sid', $id)->where('stype', $provider)->where('rid', $rid)->where('rtype', $rtype);
        })->orWhere(function ($q) use ($provider, $id, $rid, $rtype) {
            return $q->where('rid', $id)->where('rtype', $provider)->where('sid', $rid)->where('stype', $rtype);
        })->orderBy('created_at', 'desc')->paginate($request->per_page ?? 50);

        return $this->success($data);
    }

    // 发送信息
    public function send(Request $request)
    {
        $provider = $request->provider ?? 'anonymous'; // admins | users
        $ip = $request->getClientIp();
        $id = '';
        $rid = $request->rid; // 对方Id
        $rtype = $request->rtype; // 对方类型
        if ($provider != 'anonymous') {
            try {
                $id = $this->getUserId($provider);
            } catch (\Exception $e) {
                $id = md5($ip);
            }
        } else {
            $id = md5($ip);
        }

        $model = $this->getService('ChatContent', true);
        $model->sid = $id;
        $model->stype = $provider;
        $model->rid = $rid;
        $model->rtype = $rtype;
        $model->content_type = $request->content_type ?? 'text';
        $model->content = $request->content ?? '';
        $model->save();

        // 修改好友最后一次聊天时间
        $this->getService('ChatFriend', true)->orWhere(function ($q) use ($provider, $id, $rid, $rtype) {
            return $q->where('sid', $id)->where('stype', $provider)->where('rid', $rid)->where('rtype', $rtype);
        })->orWhere(function ($q) use ($provider, $id, $rid, $rtype) {
            return $q->where('rid', $id)->where('rtype', $provider)->where('sid', $rid)->where('stype', $rtype);
        })->update(['updated_at' => now()]);

        // 客户端存在 发送socket
        $client = stream_socket_client('tcp://127.0.0.1:2001', $errno, $errmsg, 1);
        fwrite($client, json_encode([
            'type'  =>  'chat',
            'data'  =>  [
                'content_type'  =>  $model->content_type,
                'content'       =>  $model->content,
                'sid'           =>  $model->sid,
                'stype'         =>  $model->stype,
                'rid'           =>  $model->rid,
                'rtype'         =>  $model->rtype,
            ]
        ]) . "\n");
        // echo fread($client, 8192);
        stream_set_timeout($client, 2);  //这个timeout 和 stream_socket_client()的参数timeout 区别是什么？？
        stream_socket_shutdown($client, STREAM_SHUT_RDWR);  //这样就算是关闭了连接，释放内存资源了

        return $this->success();
    }

    // 互相关注为好友，如果已经关注了，则不处理
    public function add(Request $request)
    {
        $provider = $request->provider ?? 'anonymous'; // admins | users
        $ip = $request->getClientIp();
        $id = '';
        $rid = $request->rid; // 对方Id
        $rtype = $request->rtype; // 对方类型
        if ($provider != 'anonymous') {
            try {
                $id = $this->getUserId($provider);
            } catch (\Exception $e) {
                $id = md5($ip);
            }
        } else {
            $id = md5($ip);
        }


        if ($id == $rid && $provider == $rtype) return $this->error("You can't talk to yourself.");

        // 判断是否有存在rid
        if (empty($rid) && empty($rtype)) {
            return $this->success(['sid' => $id, 'stype' => $provider]);
        }

        // 修改好友最后一次聊天时间
        $friend = $this->getService('ChatFriend', true)->orWhere(function ($q) use ($provider, $id, $rid, $rtype) {
            return $q->where('sid', $id)->where('stype', $provider)->where('rid', $rid)->where('rtype', $rtype);
        })->orWhere(function ($q) use ($provider, $id, $rid, $rtype) {
            return $q->where('rid', $id)->where('rtype', $provider)->where('sid', $rid)->where('stype', $rtype);
        })->first();

        if (!$friend) {
            $friend = $this->getService('ChatFriend', true);
            $friend->sid = $id;
            $friend->stype = $provider;
            $friend->rid = $rid;
            $friend->rtype = $rtype;
            $friend->updated_at = now();
            $friend->save();
        } else {
            $friend->updated_at = now();
            $friend->save();
        }
        return $this->success(['sid' => $id, 'stype' => $provider]);
    }
}
