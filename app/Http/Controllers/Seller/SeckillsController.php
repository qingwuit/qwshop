<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeckillsController extends Controller
{
    protected $modelName = 'Seckill';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['store_id'=>$storeId]));
    }

    public function store(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $request->offsetSet('store_id', $storeId);
        $request->offsetSet('end_time', now());
        if ($request->discount>100) {
            return $this->error(__('tip.discount.over100'));
        }
        return $this->handle($this->getService('base')->addDat($this->modelName, ['store_id','goods_id','discount','start_time','end_time']));
    }

    public function update(Request $request, $id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        if ($request->discount>100) {
            return $this->error(__('tip.discount.over100'));
        }
        return $this->handle($this->getService('base')->editDat($this->modelName, $id, ['goods_id','discount','start_time','end_time'], ['store_id'=>$storeId]));
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->delDat($this->modelName, $id, ['store_id'=>$storeId]));
    }
}
