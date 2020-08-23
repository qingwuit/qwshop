<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\AgreementService;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    public function show(AgreementService $agreement_service,$ename){
        $info = $agreement_service->getAgreement($ename);
        if($info['status']){
            return $this->success($info['data']);
        }else{
            return $this->error($info['msg']);
        }
    }
}
