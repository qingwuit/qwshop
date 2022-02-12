<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoneyLogsController extends Controller
{
    protected $modelName = 'MoneyLog';
    protected $auth = 'users';

    public function index(Request $request)
    {
        $data = $this->getService('base')->getPageData($this->modelName, ['user_id' => $this->getService('Store')->getStoreId()['data'], 'is_belong' => 1]);
        return $this->handle($data);
    }
}
