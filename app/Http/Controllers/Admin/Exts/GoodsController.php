<?php

namespace App\Http\Controllers\Admin\Exts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    protected $modelName = 'Goods';

    public function show($id)
    {
        $rs = $this->getService('Goods')->getGoodsInfo($id);
        return $this->handle($rs);
    }

    public function update(Request $request, $id)
    {
        $rs = $this->getService('Goods')->editGoods($id,'admins');
        return $this->handle($rs);
    }
}
