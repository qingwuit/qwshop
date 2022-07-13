<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvSpacesController extends Controller
{
    // 获取幻灯片广告
    public function index(Request $request){
        return $this->handle($this->getService('Adv')->adv($request->name??''));
    }
}
