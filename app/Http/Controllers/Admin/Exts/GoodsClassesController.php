<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoodsClassesController extends Controller
{
    protected $modelName = 'GoodsClass';

    public function loadMenu(Request $request)
    {
        $treeType = $request->type??'getChildren';
        $deep = $request->deep??-1;
        // if(!auth($this->auth)->check()) return $this->auto(405,'No Login.',[]);

        $data = [];
        $data = $this->getService($this->modelName, true)->select('id', 'name', 'thumb', 'pid')->orderBy('is_sort', 'asc')->get();
       
        try {
            $tree = $this->getService('Tool')->$treeType($data, 0, 0, $deep);
        } catch (\Exception $e) {
            return $this->error(__('tip.failed'));
        }
        return $this->success($tree);
    }
}
