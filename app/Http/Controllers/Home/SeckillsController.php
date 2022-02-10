<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeckillsController extends Controller
{
    public $modelName = 'Seckill';

    public function index(Request $request)
    {
        return $this->handle($this->getService('Goods')->getHomeSeckillGoods());
    }
}
