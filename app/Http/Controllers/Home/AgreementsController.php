<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgreementsController extends Controller
{
    public function agreement($name){
        $agreement = $this->getService('Agreement',true)->where('ename',$name)->first();
        if($agreement) return $this->success($agreement);
        return $this->error();
    }
}
