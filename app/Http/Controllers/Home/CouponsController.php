<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public $modelName = 'CouponLog';
    protected $setUser = true;
    protected $auth = 'users';
    protected $belongName = 'user_id';

    public function receive()
    {
        return $this->handle($this->getService('Coupon')->receive());
    }
}
