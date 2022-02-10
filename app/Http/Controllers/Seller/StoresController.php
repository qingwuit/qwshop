<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    protected $modelName = 'Store';
    protected $auth = 'users';

    // 获取店铺分类
    public function store_classes()
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $rs = $this->getService('StoreClass', true)->where('store_id', $storeId)->first();
        if ($rs && !empty($rs->class_id)) {
            $class_id = [];
            $classId = json_decode($rs->class_id, true);
            foreach ($classId as $v) {
                foreach ($v as $vo) {
                    $class_id[] = intval($vo);
                }
            }
            $class = $this->getService('GoodsClass', true)->whereIn('id', $class_id)->get();
            $data = $this->getService('Tool')->getChildren($class);
            return $this->success($data);
        } else {
            return $this->error('class not found');
        }
    }

    // 获取店铺配置
    public function show($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $rs = $this->getService('Store')->getStoreInfo($storeId);
        return $this->handle($rs);
    }

    // 修改
    public function update(Request $request, $id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $rs = $this->getService('Store')->editStore($storeId);
        return $this->handle($rs);
    }
}
