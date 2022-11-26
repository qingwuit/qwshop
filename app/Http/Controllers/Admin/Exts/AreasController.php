<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaBaseCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AreasController extends Controller
{
    protected $modelName = 'Area';

    public function index(Request $request)
    {
        return $this->handle($this->getService('base')->getPageData($this->modelName, [], 'id asc'));
    }

    public function loadArea(Request $request)
    {
        $treeType = $request->type ?? 'getAreaChildren';
        $deep = $request->deep ?? -1;

        // 懒加载数据
        if (isset($request->isLazy) && $request->isLazy) {
            $pid = $request->pid ?? 0;
            $area = $this->getService('Area', true)->where('pid', $pid)->orderBy('id')->get();
            return $this->success(new AreaBaseCollection($area));
        }

        $data = $this->getService('Area', true)->select('id', 'name', 'code', 'pid', 'deep')->get();
        try {
            if (Cache::has('area') && $deep == -1) return $this->success(Cache::get('area'));
            $tree = $this->getService('Tool')->$treeType($data, 0, 0, $deep);
            if ($deep == -1) Cache::put('area', $tree);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return $this->error(__('tip.failed'));
        }
        return $this->success($tree);
    }

    // 清除缓存
    public function clear_area()
    {
        if (Cache::has('area')) {
            Cache::forget('area');
        }
        $data = $this->getService('Area', true)->select('id', 'name', 'code', 'pid')->get();
        $tree = $this->getService('Tool')->getAreaChildren($data, 0, 0, -1);
        Cache::put('area', $tree);
        return $this->success($tree);
    }
}
