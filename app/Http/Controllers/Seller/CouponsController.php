<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    protected $modelName = 'Coupon';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['store_id'=>$storeId]));
    }

    public function store(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $request->offsetSet('store_id', $storeId);
        return $this->handle($this->getService('base')->addDat($this->modelName, ['store_id','name','money','use_money','stock','start_time','end_time']));
    }

    public function update(Request $request, $id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->editDat($this->modelName, $id, ['name','money','use_money','stock','start_time','end_time'], ['store_id'=>$storeId]));
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->delDat($this->modelName, $id, ['store_id'=>$storeId]));
    }
}
