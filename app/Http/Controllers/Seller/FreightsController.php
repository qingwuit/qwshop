<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FreightsController extends Controller
{
    protected $modelName = 'Freight';

    public function index(Request $request)
    {
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['store_id'=>$this->getService('Store')->getStoreId()['data']], 'is_type asc'));
    }

    public function update(Request $request, $id)
    {
        return $this->handle($this->getService('Freight')->edit());
    }

    public function destroy($id)
    {
        $model = $this->getService($this->modelName, true);
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $idArray = array_filter(explode(',', $id), function ($item) {
            return is_numeric($item);
        });
        $rs = $model->where('store_id', $storeId)->whereIn('id', $idArray)->delete();
        return $this->success($rs);
    }
}
