<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoodsAttrsController extends Controller
{
    protected $modelName = 'GoodsAttr';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $rs = $this->getService('Base')->getPageData($this->modelName, ['store_id'=>$storeId]);
        return $this->handle($rs);
    }

    public function store(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $attrId = $this->getService($this->modelName, true)->insertGetId([
            'store_id'  =>  $storeId??0,
            'name'  =>  $request->name??'',
            'created_at'  =>  now(),
        ]);
        $specs = $request->specs??[];
        if (!empty($specs)) {
            $specsData = [];
            foreach ($specs as $v) {
                $specsData[] = [
                    'attr_id'=>$attrId,
                    'name'=>$v,
                ];
            }
            $this->getService('GoodsSpecs', true)->insert($specsData);
        }
        return $this->success($attrId);
    }

    public function update(Request $request, $id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $this->getService($this->modelName, true)->where('store_id', $storeId)->where('id', $id)->update(['name'=>$request->name??'']);
        $this->getService('GoodsSpecs', true)->where('attr_id', $id)->delete(); // 删除所有规格
        $specs = $request->specs??[];
        if (!empty($specs)) {
            $specsData = [];
            foreach ($specs as $v) {
                $specsData[] = [
                    'attr_id'=>$id,
                    'name'=>$v,
                ];
            }
            $this->getService('GoodsSpecs', true)->insert($specsData);
        }
        return $this->success($id);
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        try {
            $rs = $this->getService('base')
                        ->delDat($this->modelName, $id, ['store_id'=>$storeId]);
            $this->getService('GoodsSpecs', true)->whereIn('attr_id', explode('id', $id))->delete();
            return $this->handle($rs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
