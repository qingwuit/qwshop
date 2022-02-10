<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DistributionLogsController extends Controller
{
    protected $modelName = 'DistributionLog';

    public function index(Request $request)
    {
        $storeId = $this->getService('Store')->getStoreId()['data'];
        return $this->handle($this->getService('base')->getPageData($this->modelName, ['store_id'=>$storeId]));
    }
}
