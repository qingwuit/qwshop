<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectivesController extends Controller
{
    protected $modelName = 'Collective';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['store_id'=>$storeId]));
    }

    public function store(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $request->offsetSet('store_id', $storeId);
        if ($request->discount>100) {
            return $this->error(__('tip.discount.over100'));
        }
        if ($request->need<2) {
            return $this->error(__('tip.discount.need'));
        }
        return $this->handle($this->getService('base')->addDat($this->modelName, ['store_id','goods_id','discount','need']));
    }

    public function update(Request $request, $id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        if ($request->discount>100) {
            return $this->error(__('tip.discount.over100'));
        }
        if ($request->need<2) {
            return $this->error(__('tip.discount.need'));
        }
        return $this->handle($this->getService('base')->editDat($this->modelName, $id, ['goods_id','discount','need'], ['store_id'=>$storeId]));
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->delDat($this->modelName, $id, ['store_id'=>$storeId]));
    }
}
