<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderCommentsController extends Controller
{
    protected $modelName = 'OrderComment';
    protected $auth = 'users';

    public function index(Request $request)
    {
        $data = $this->getService('base')->getPageData($this->modelName, ['store_id'=>$this->getService('Store')->getStoreId()['data']]);
        return $this->handle($data);
    }

    public function update(Request $request, $id)
    {
        $request->offsetSet('reply_time', now());
        return $this->handle($this->getService('base')->editDat($this->modelName, $id, ['reply','reply_time'], ['store_id'=>$this->getService('Store')->getStoreId()['data']]));
    }
}
