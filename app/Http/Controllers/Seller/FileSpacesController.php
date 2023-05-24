<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileSpacesController extends Controller
{
    protected $modelName = 'FileSpace';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['belong_id'=>$storeId]));
    }

    public function destroy($id)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        $idArray = array_filter(explode(',', $id), function ($item) {
            return (is_numeric($item));
        });
        $this->getService($this->modelName, true)->whereIn('id', $idArray)->delete();
        return $this->success();
    }
}
