<?php

namespace App\Workerman;

use \GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Log;

class Events
{

    public static function onWorkerStart($businessWorker){
    }

    // socket 连接成功
    public static function onConnect($client_id){
        // Gateway::sendToAll("$client_id said connect \r\n");
        // Log::channel('qwlog')->info('client_id:'.$client_id);
    }

    public static function onWebSocketConnect($client_id, $data){
        // Gateway::sendToAll("$client_id said  onWebSocketConnect \r\n");
        // Log::channel('qwlog')->info('client_id:'.'____onWebSocketConnect__:'.$data);
    }

    public static function onMessage($client_id, $message){
        $data = json_decode($message,true);
        // Log::channel('qwlog')->info('client_id:'.$client_id.$message);
    }

    public static function onClose($client_id){
    }

    // 绑定用户ID
    public function bindUid($cid,$uid){
        if(empty($cid) || empty($uid)){
            echo "用户ID 不能为空！";
        }
        Gateway::bindUid($cid,$uid);
    }
}