<?php
namespace App\Services;

use App\Models\Agreement;

class AgreementService extends BaseService{

    // 获取协议内容
    public function getAgreement($ename){
        $agreement_model = new Agreement();
        $info = $agreement_model->select('title','content')->where('ename',$ename)->first();
        if(empty($info)){
            return $this->format_error(__('admins.agreement_not_defined'));
        }
        return $this->format($info);
    }
}
