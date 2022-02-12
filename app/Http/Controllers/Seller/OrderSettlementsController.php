<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderSettlementsController extends Controller
{
    protected $modelName = 'OrderSettlement';
    protected $auth = 'users';

    public function index(Request $request)
    {
        $data = $this->getService('base')->getPageData($this->modelName, ['store_id' => $this->getService('Store')->getStoreId()['data']]);
        return $this->handle($data);
    }

    public function show($id)
    {
        $rs = $this->getService($this->modelName, true)->where('id', $id)->where('store_id', $this->getService('Store')->getStoreId()['data'])->first();
        return $this->success($rs);
    }
}
