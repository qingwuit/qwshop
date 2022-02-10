<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DistributionsController extends Controller
{
    protected $modelName = 'Distribution';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['store_id'=>$storeId]));
    }

    public function store(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $request->offsetSet('store_id', $storeId);
        return $this->handle($this->getService('base')->addDat($this->modelName, ['store_id','goods_id','lev_1','lev_2','lev_3']));
    }

    public function update(Request $request, $id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->editDat($this->modelName, $id, ['goods_id','lev_1','lev_2','lev_3'], ['store_id'=>$storeId]));
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->delDat($this->modelName, $id, ['store_id'=>$storeId]));
    }
}
