<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashesController extends Controller
{
    public $modelName = 'Cash';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function store(Request $request)
    {
        return $this->handle($this->getService('Cash')->add());
    }
}
