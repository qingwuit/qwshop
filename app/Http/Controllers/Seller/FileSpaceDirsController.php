<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileSpaceDirsController extends Controller
{
    protected $modelName = 'FileSpaceDir';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['belong_id'=>$storeId]));
    }

    public function store(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        request()->merge(['belong_id'=>$storeId]);
        $rs = $this->getService('base')->addDat($this->modelName);
        return $this->handle($rs);
    }

    public function update(Request $request, $id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $rs = $this->getService('base')->editDat($this->modelName,$id,[],['belong_id'=>$storeId]);
        return $this->handle($rs);
    }

    public function show($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $rs = $this->getService('base')->findDat($this->modelName,$id,[],['belong_id'=>$storeId]);
        return $this->handle($rs);
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $idArray = array_filter(explode(',', $id), function ($item) {
            return (is_numeric($item));
        });
        $dirList = $this->getService($this->modelName, true)->select('id')->where('belong_id', $storeId)->whereIn('id', $idArray)->get();
        if ($dirList->isEmpty()) {
            return $this->success();
        }
        $dirId = [];
        foreach ($dirList as $v) {
            $dirId[] = $v->id;
        }
        $this->getService('FileSpace', true)->whereIn('dir_id', $dirId)->delete();
        $this->getService('FileSpaceDir', true)->whereIn('id', $idArray)->delete();
        return $this->success();
    }
}
