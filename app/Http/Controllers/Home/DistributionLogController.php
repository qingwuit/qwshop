<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\DistributionService;
use App\Services\ToolService;
use App\Services\UserService;
use Illuminate\Http\Request;

class DistributionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DistributionService $dis_service)
    {
        $rs = $dis_service->getLogList();
        return $rs['status']?$this->success($rs['data']):$this->error();
    }

    // 获取分销下所有用户
    public function user(DistributionService $dis_service){
        $rs = $dis_service->getUser();
        return $rs['status']?$this->success($rs['data']):$this->error();
    }

    // 获取分享链接和图片
    public function link(){
        $user_service = new UserService();
        $tool_service = new ToolService();
        $user_info = $user_service->getUserInfo();
        $qrcode = $tool_service->create_qrcode(env('APP_URL').'/m/user/register?inviter_id='.$user_info['id']);
        $link = env('APP_URL').'/user/register?inviter_id='.$user_info['id'];
        return $this->success(['qrcode'=>$qrcode,'link'=>$link]);
    }

    
}
