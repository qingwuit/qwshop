<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoneyLogsController extends Controller
{
    protected $modelName = 'MoneyLog';
    protected $auth = 'users';
    protected $belongName = 'user_id';
    protected $setUser = true;
}
