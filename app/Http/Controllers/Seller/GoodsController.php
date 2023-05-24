<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public $modelName = 'Goods';
    public $auth = 'users';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $rs = $this->getService('Base')->getPageData($this->modelName, ['store_id'=>$storeId]);
        return $this->handle($rs);
    }
    
    public function store(Request $request)
    {
        $rs = $this->getService('Goods')->addGoods();
        return $this->handle($rs);
    }

    public function update(Request $request, $id)
    {
        $rs = $this->getService('Goods')->editGoods($id);
        return $this->handle($rs);
    }

    public function show($id)
    {
        $rs = $this->getService('Goods')->getGoodsInfo($id);
        return $this->handle($rs);
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $idArray = array_filter(explode(',', $id), function ($item) {
            return (is_numeric($item));
        });
        $goodsList = $this->getService('Goods', true)->select('id')->where('store_id', $storeId)->whereIn('id', $idArray)->get();
        if ($goodsList->isEmpty()) {
            return $this->success();
        }
        $goodsId = [];
        foreach ($goodsList as $v) {
            $goodsId[] = $v->id;
        }
        $this->getService('GoodsSku', true)->where('goods_id', $goodsId)->delete();
        $this->getService('Goods', true)->whereIn('id', $goodsId)->delete();
        return $this->success();
    }
}
