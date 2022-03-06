<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function join()
    {
        return $this->handle($this->getService('Store')->createStore());
    }

    public function edit()
    {
        return $this->handle($this->getService('Store')->editStore(true));
    }

    // 获取详情
    public function info()
    {
        try {
            $data = $this->getService('Store')->getStoreInfo(true);
            return $this->handle($data);
        } catch (\Exception $e) {
            return $this->success([], $e->getMessage());
        }
    }

    // 获取列表
    public function stores()
    {
        return $this->handle($this->getService('Store')->stores());
    }

    // 无权限获取商品信息
    public function show($id)
    {
        $data = $this->getService('Store')->getStoreInfoAndRate($id);
        $data['data']['sale_list'] = $this->getService('Goods')->sortGoods($id)['data'];
        return $this->handle($data);
    }
}
