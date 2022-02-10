<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoneyLogsController extends Controller
{
    public $modelName = 'MoneyLog';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function index(Request $request)
    {
        return $this->handle($this->getService('MoneyLog')->getMoneyLog());
    }
}
