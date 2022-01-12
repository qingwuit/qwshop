<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    // 首页公共数据
    public function common(){
        $data['classes'] = $this->getService('Tool')->getChildren($this->getService('GoodsClass',true)->orderBy('is_sort','asc')->get()); // 商品分类
        $data['brands'] = $this->getService('GoodsBrand',true)->orderBy('is_sort','asc')->get(); // 商品品牌

        // 可以使用 Resource 控制达到脱敏
        $data['common'] = $this->getService('Configs')->getFormatConfig([
            'web_name','logo','index_name','keyword','description','mobile','email','icp','close_status','amap','close_reason'
        ]);
        $data['ip'] = request()->getClientIp();
        
        // 购物车数据
        $data['cart'] = $this->getService('Cart')->getCount()['data'];
        
        return $this->success($data);
    }

    // 首页信息
    public function home(){
        $data['goods'] = $this->getService('Goods')->master(8)['data'];
        return $this->success($data);
    }
    
}
