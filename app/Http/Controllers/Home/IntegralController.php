<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegralController extends Controller
{
    protected $modelName = 'IntegralGoods';
    public function home()
    {
        $data['recommend'] = $this->getService('IntegralGoods', true)->where('goods_status', 1)->where('is_recommend', 1)->take(4)->orderBy('goods_sale', 'desc')->get();
        $data['list'] = $this->getService('IntegralGoodsClass', true)->with(['integral_goods'=>function ($q) {
            $q->where('goods_status', 1)->take(4)->orderBy('goods_sale', 'desc');
        }])->get();
        $data['banner'] =  []; // $adv_service->getAdvList('PC_积分商城幻灯片')['data'];
        return $this->success($data);
    }

    public function integral_class()
    {
        return $this->success($this->getService('IntegralGoodsClass', true)->get());
    }

    public function index(Request $request)
    {
        return $this->handle($this->getService('IntegralGoods')->search());
    }

    public function pay()
    {
        return $this->handle($this->getService('IntegralGoods')->createOrder());
    }

    // 获取订单列表
    public function integral_orders()
    {
        return $this->handle($this->getService('base')->getPageData('IntegralOrder'));
    }
}
