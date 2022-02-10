<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegralGoodsController extends Controller
{
    protected $modelName = 'IntegralGoods';

    public function store(Request $request)
    {
        $request->offsetSet('goods_status', $request->goods_status=='true'?1:0);
        $request->offsetSet('goods_images', implode(',', $request->goods_images));
        $request->offsetSet('is_recommend', $request->is_recommend=='true'?1:0);
        return $this->handle($this->getService('base')->addDat($this->modelName));
    }

    public function update(Request $request, $id)
    {
        $request->offsetSet('goods_status', $request->goods_status=='true'?1:0);
        $request->offsetSet('is_recommend', $request->is_recommend=='true'?1:0);
        $request->offsetSet('goods_images', implode(',', $request->goods_images));
        return $this->handle($this->getService('base')->editDat($this->modelName, $id));
    }
}
